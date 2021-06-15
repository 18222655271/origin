<?php

use addons\kefu\library\Common;
use GatewayWorker\Lib\Gateway;
use think\Db;

/**
 *
 */
class Message
{
    public function __construct()
    {

    }

    /*清退链接*/
    public function clear($client_id)
    {
        Gateway::closeClient($client_id);
    }

    /*用户端心跳*/
    public function ping()
    {
    }

    /**
     * 推送消息-主要用于推送小程序端发送的消息到客服
     */
    public function pushMessage($client_id, $data)
    {
        //think\Log::record('push_message' . json_encode($data, true), 'notice');

        $_SESSION['user_id'] = $data['sender'];
        $_SESSION['cdn_url'] = $data['origin'];

        // 标记消息已读
        Db::name('kefu_record')
            ->where('session_id', $data['session_id'])
            ->where('sender_identity', 0)
            ->where('status', 0)
            ->update(['status' => 1]);

        $session = Db::name('kefu_session')->where('id', $data['session_id'])->find();
        if ($session) {
            $session_user = Common::sessionUser($session, $data['sender']);
            if (Gateway::isUidOnline($session_user)) {
                Gateway::sendToUid($session_user, json_encode([
                    'msgtype' => 'read_message_done',
                    'data'    => [
                        'session_id' => $data['session_id'],
                        'record_id'  => 'all'
                    ]
                ]));
            }
        }

        Common::chatRecord($data['session_id'], $data['message'], $data['message_type'], $data['sender']);
    }

    /**
     * 客服代表改变状态
     */
    public function csrChangeStatus($client_id, $data)
    {
        if (isset($data['status'])) {
            $status = Common::csrStatus($data['status']);

            // 向所有人发送
            Gateway::sendToAll(json_encode([
                'msgtype' => 'csr_change_status',
                'data'    => [
                    'csr_status' => $data['status'],
                    'csr'        => $_SESSION['user_id'],
                ],
                'code'    => 1,
            ]));
        }
    }

    /*
     * 直接标记消息已读
     */
    public function readMessage($client_id, $data)
    {
        if (isset($data['record_id']) && $data['record_id']) {
            // 查得会话信息
            if (Db::name('kefu_record')
                    ->where('id', (int)$data['record_id'])
                    ->update(['status' => 1]) && isset($data['session_id']) && $data['session_id']) {

                $session = Db::name('kefu_session')->where('id', (int)$data['session_id'])->find();
                if ($session) {
                    $session_user = Common::sessionUser($session);
                    if (Gateway::isUidOnline($session_user)) {
                        Gateway::sendToUid($session_user, json_encode([
                            'msgtype' => 'read_message_done',
                            'data'    => [
                                'session_id' => $data['session_id'],
                                'record_id'  => $data['record_id']
                            ]
                        ]));
                    }
                }
            }
        }
    }

    /*
     * 用户打开聊天窗口->分配客服->获取聊天记录
     */
    public function userInitialize($client_id, $data)
    {
        if (!isset($_SESSION['user_id']) || !$_SESSION['user_id']) {
            return;
        }

        $auto_distribution_csr = true;// 是否需要自动分配客服代表

        $user_info = Common::userInfo($_SESSION['user_id']);
        if ($user_info['source'] == 'csr') {
            common::showMsg($client_id, '无法为客服成员分配客服代表！');
            return;
        }

        // 查询之前的客服代表
        $session = Db::name('kefu_session')
            ->alias('s')
            ->field('s.*,a.id as admin_id,a.nickname')
            ->join('admin a', 's.csr_id=a.id')
            ->where('s.user_id', $user_info['id'])
            ->where('s.deletetime', null)
            ->find();

        // 客服状态
        if ($session) {
            $csr_status = Db::name('kefu_csr_config')->where('admin_id', $session['admin_id'])->value('status');

            // 上次的客服代表在线
            if ($csr_status && $csr_status == 3) {
                $auto_distribution_csr = false;
            }
        }

        // 前台指定客服
        // 有手动转移客服的记录时指定无效
        $is_transfer = Db::name('kefu_trajectory')
            ->where('user_id', $user_info['id'])
            ->where('log_type', 8)
            ->value('id');

        if (!$is_transfer && isset($data['fixed_csr']) && (int)$data['fixed_csr'] > 0) {

            $csr_admin_id = Db::name('kefu_csr_config')->where('id', $data['fixed_csr'])->value('admin_id');
            if (!$csr_admin_id) {
                common::showMsg($client_id, '指定的用户不是客服代表！');
                return;
            }

            $auto_distribution_csr = false;

            if (!$session || $session['csr_id'] != $csr_admin_id) {
                $session = Common::distributionCsr($csr_admin_id . '||csr', $_SESSION['user_id']);
            }
        }

        // 自动分配客服代表
        if ($auto_distribution_csr) {
            $csr = Common::getAppropriateCsr();
            if ($csr) {
                $session = Common::distributionCsr($csr, $_SESSION['user_id']);
            } else {
                Gateway::sendToClient($client_id, json_encode([
                    'msgtype' => 'user_initialize',
                    'code'    => 302,
                    'msg'     => '无在线客服！',
                ]));
                return;
            }
        }

        if ($session) {

            // 客服状态
            $session['csr_status'] = Db::name('kefu_csr_config')
                ->where('admin_id', $session['admin_id'])
                ->value('status');

            $session['csr'] = $session['csr_id'] . '||csr';

            // 记录客服接待人数
            Db::name('kefu_csr_config')->where('admin_id', $session['admin_id'])->inc('reception_count')->update([
                'last_reception_time' => time(),
            ]);

            // 获取聊天记录
            $this->chatRecord($client_id, [
                'session_id' => $session['id'],
            ]);

            // 游客且有绑定会员
            if (isset($_SESSION['is_tourists']) && $_SESSION['is_tourists'] && $user_info['user_id']) {

                $session['user_tourists']  = true;
                $session['user_login_url'] = url('index/user/login');
            }

            $initialize_data['session'] = $session;

            Gateway::sendToClient($client_id, json_encode([
                'msgtype' => 'user_initialize',
                'code'    => 1,
                'data'    => $initialize_data,
            ]));
        } else {
            common::showMsg($client_id, '分配客服代表失败，请重试！');
            return;
        }
    }

    /*
     * 加载更多聊天记录
     */
    public function chatRecord($client_id, $data)
    {
        $page_count = 20; //一次加载20条

        if (!isset($data['page'])) {
            $data['page'] = 1;
        }

        if (!isset($data['session_id'])) {
            Gateway::sendToClient($client_id, json_encode([
                'msgtype' => 'chat_record',
                'code'    => 1,
                'data'    => [
                    'chat_record'  => [],
                    'session_info' => [
                        'nickname' => '无会话',
                    ],
                    'next_page'    => 'done',
                    'page'         => $data['page'],
                ],
            ]));

            return '';
        }

        $chat_record_count = Db::name('kefu_record')->where('session_id', $data['session_id'])->count('id');

        $page_number = ceil($chat_record_count / $page_count);

        if ($data['page'] == 1) {
            $min = 0;
        } else {
            $min = ($data['page'] - 1) * $page_count;
        }

        // 会话信息
        $session_info = Db::name('kefu_session')->where('id', $data['session_id'])->find();

        if (!$session_info) {
            common::showMsg($client_id, '会话找不到啦！');
            return;
        }

        $session_user                      = Common::sessionUser($session_info);
        $session_user_info                 = Common::userInfo($session_user);
        $session_user_info['session_user'] = $session_user;

        // 标记此会话所有不是当前用户发的消息为已读->SQL不使用不等于->查得会话对象的ID
        Db::name('kefu_record')
            ->where('session_id', $data['session_id'])
            ->where('sender_identity', $session_user_info['source'] == 'csr' ? 0 : 1)
            // ->where('sender_id', $session_user_info['id'])
            ->where('status', 0)
            ->update(['status' => 1]);

        if (Gateway::isUidOnline($session_user)) {
            Gateway::sendToUid($session_user, json_encode([
                'msgtype' => 'read_message_done',
                'data'    => [
                    'session_id' => $data['session_id'],
                    'record_id'  => 'all'
                ]
            ]));
        }

        // 是否屏蔽此人
        $session_user_info['blacklist'] = Db::name('kefu_blacklist')
            ->where('user_id', $session_info['user_id'])
            ->value('id');

        $chat_record = Db::name('kefu_record')
            ->where('session_id', $data['session_id'])
            ->limit($min, $page_count)
            ->order('createtime desc,id desc')
            ->select();

        if ($data['page'] == 1) {
            $chat_record = array_reverse($chat_record, false);
        }

        $user_info = Common::userInfo($_SESSION['user_id']);

        $tourists_record = false;
        if (isset($_SESSION['is_tourists']) && $_SESSION['is_tourists'] && $user_info['user_id']) {

            // 游客且有绑定会员
            $tourists_record[] = [
                'datetime' => '为保护您的隐私,您需要登录后才能查看历史聊天记录',
                'data'     => [],
            ];
        }

        // 消息按时间分组
        if ($chat_record && !$tourists_record) {

            $record_temp = [];
            $createtime  = $chat_record[0]['createtime'];

            foreach ($chat_record as $key => $value) {

                if (($value['sender_identity'] == 0 && $user_info['source'] == 'csr') || ($value['sender_identity'] == 1 && $user_info['source'] == 'user')) {
                    $value['sender'] = 'me';
                } else {
                    $value['sender'] = 'you';
                }

                if ($value['message_type'] == 1 || $value['message_type'] == 2) {
                    $value['message'] = Common::imgSrcFill($value['message'], false);
                } else {
                    $value['message'] = htmlspecialchars_decode($value['message']);
                }

                if (($value['createtime'] - $createtime) < 3600) {
                    $record_temp[$createtime][] = $value;
                } else {
                    $createtime                 = $value['createtime'];
                    $record_temp[$createtime][] = $value;
                }
            }

            unset($chat_record);

            foreach ($record_temp as $key => $value) {
                $chat_record[] = [
                    'datetime' => Common::formatTime($key),
                    'data'     => $value,
                ];
            }
            unset($record_temp);
        } elseif ($tourists_record) {
            $chat_record = $tourists_record;
        } else {
            $chat_record[] = [
                'datetime' => '还没有消息',
                'data'     => [],
            ];
        }

        Gateway::sendToClient($client_id, json_encode([
            'msgtype' => 'chat_record',
            'code'    => 1,
            'data'    => [
                'chat_record'  => $chat_record,
                'session_info' => $session_user_info,
                'next_page'    => ($data['page'] >= $page_number) ? 'done' : $data['page'] + 1,
                'page'         => $data['page']
            ],
        ]));
    }

    /**
     * 更新云存储 multipart
     */
    public function getUploadMultipart($client_id)
    {
        // 获取上传配置
        $upload = \app\common\model\Config::upload();
        \think\Hook::listen("upload_config_init", $upload);

        Gateway::sendToClient($client_id, json_encode([
            'msgtype' => 'upload_multipart',
            'code'    => 1,
            'data'    => [
                'upload_multipart' => isset($upload['multipart']) ? $upload['multipart'] : []
            ],
        ]));
    }

    /**
     * 记录留言
     */
    public function leaveMessage($client_id, $data)
    {
        if (!$data['contact']) {
            common::showMsg($client_id, '联系方式不能为空~');
            return;
        }

        $user_info = Common::userInfo($_SESSION['user_id']);

        if ($user_info['source'] == 'csr') {

            common::showMsg($client_id, '客服代表无需留言~');
            return;
        }

        $data['user_id']    = $user_info['id'];
        $data['createtime'] = time();

        $last_leave_message_time = Db::name('kefu_leave_message')
            ->where('user_id', $user_info['id'])
            ->order('createtime desc')
            ->value('createtime');

        if ($last_leave_message_time && ($last_leave_message_time + 600) > $data['createtime']) {
            common::showMsg($client_id, '请勿频繁留言，请稍后再试~');
            return;
        }

        // 入库
        if (Db::name('kefu_leave_message')->insert($data)) {

            $leave_message_id = Db::name('kefu_leave_message')->getLastInsID();

            // 记录轨迹
            $trajectory = [
                'user_id'    => $user_info['id'],
                'csr_id'     => 0,
                'log_type'   => 6,
                'note'       => $leave_message_id,
                'url'        => '',
                'referrer'   => '',
                'createtime' => time(),
            ];
            Db::name('kefu_trajectory')->insert($trajectory);

            Gateway::sendToClient($client_id, json_encode([
                'msgtype' => 'leave_message',
                'code'    => 1,
                'msg'     => '留言成功~',
            ]));

            $new_message_notice = Db::name('kefu_config')->where('name', 'new_message_notice')->value('value');
            if ($new_message_notice) {

                $markdown = '### 有新的留言 - ' . $user_info['nickname'] . PHP_EOL . '##### 联系姓名:' . $data['name'] . PHP_EOL . '##### 联系方式:' . $data['contact'] . PHP_EOL . '##### 留言内容:' . $data['message'];

                Common::dingNotice($new_message_notice, '有新的留言', $markdown, true);
            }

        } else {
            common::showMsg($client_id, '留言失败，请重试~');
            return;
        }
    }

    /**
     * 用户名片
     */
    public function userCard($client_id, $data)
    {
        if (!isset($data['session_user'])) {
            common::showMsg($client_id, '用户未找到');
            return;
        }

        // 用户信息
        $user_info = Common::userInfo($data['session_user']);
        if (!$user_info) {
            common::showMsg($client_id, '用户未找到');
            return;
        }

        if (isset($data['action']) && $data['action'] == 'done') {
            if (Db::name('kefu_user')->where('id', $user_info['id'])->update($data['form_data'])) {

                common::showMsg($client_id, '保存成功！');
                Gateway::sendToClient($client_id, json_encode([
                    'msgtype' => 'user_card',
                    'code'    => 1,
                    'data'    => Common::userInfo($data['session_user']),
                ]));
            } else {
                common::showMsg($client_id, '保存失败，请重试！');
            }

            return;
        }

        Gateway::sendToClient($client_id, json_encode([
            'msgtype' => 'user_card',
            'code'    => 1,
            'data'    => $user_info,
        ]));
    }

    /*
     * 邀请对话 & 删除会话 & 转接会话 & 修改用户昵称
     */
    public function actionSession($client_id, $data)
    {

        if (!isset($data['session_user'])) {
            common::showMsg($client_id, '用户未找到');
            return;
        }

        // 用户信息
        $user_info = Common::userInfo($data['session_user']);
        if (!$user_info) {
            common::showMsg($client_id, '用户未找到');
            return;
        }

        if ($data['action'] == 'del') {

            $session_info = Db::name('kefu_session')
                ->where('user_id', $user_info['id'])
                ->where('deletetime', null)
                ->find();
            if (!$session_info) {
                common::showMsg($client_id, '会话找不到啦！');
                return;
            }

            if (!Db::name('kefu_session')->where('user_id', $user_info['id'])->update(['deletetime' => time()])) {
                common::showMsg($client_id, '会话删除失败！');
                return;
            }

        } elseif ($data['action'] == 'invitation') {
            if (Gateway::isUidOnline($data['session_user'])) {

                $user     = explode('||', $data['session_user']);
                $csr_info = Common::userInfo($_SESSION['user_id']);

                // 记录轨迹
                $trajectory = [
                    'user_id'    => $user[0],
                    'csr_id'     => $csr_info['id'],
                    'log_type'   => 1,
                    'note'       => $csr_info['nickname'] . ' 邀请对话',
                    'url'        => '',
                    'referrer'   => '',
                    'createtime' => time(),
                ];
                Db::name('kefu_trajectory')->insert($trajectory);

                Gateway::sendToUid($data['session_user'], json_encode([
                    'code'    => 1,
                    'msgtype' => 'action_session',
                    'data'    => [
                        'action' => 'received_invitation',
                    ],
                ]));

                Gateway::sendToClient($client_id, json_encode([
                    'code'    => 1,
                    'msgtype' => 'action_session',
                    'data'    => [
                        'action'       => 'send_success',
                        'session_user' => $data['session_user'],
                    ],
                ]));
            } else {
                common::showMsg($client_id, '邀请对话失败，用户已下线！');
                return;
            }
        } elseif ($data['action'] == 'transfer') {

            $csr_info = Common::userInfo($_SESSION['user_id']);

            // 转接会话->返回客服列表
            $csr_list = Db::name('kefu_csr_config')
                ->alias('c')
                ->field('c.admin_id,a.nickname')
                ->join('admin a', 'a.id=c.admin_id')
                ->where('c.status', 3)
                ->select();

            foreach ($csr_list as $key => $value) {
                if ($value['admin_id'] == $csr_info['id']) {
                    unset($csr_list[$key]);
                    break;
                }
            }

            Gateway::sendToClient($client_id, json_encode([
                'code'    => 1,
                'msgtype' => 'action_session',
                'data'    => [
                    'action'       => 'transfer',
                    'csr_list'     => $csr_list,
                    'session_user' => $data['session_user'],
                ],
            ]));

        } elseif ($data['action'] == 'transfer_done') {

            // 转接会话
            if (!isset($data['csr']) || !isset($data['session_user'])) {

                common::showMsg($client_id, '会话转移失败，请重试~');
                return;
            }

            $session_user_info = Common::userInfo($data['session_user']);
            $current_csr_info  = Common::userInfo($_SESSION['user_id']);

            if ($current_csr_info['id'] == $data['csr']) {

                common::showMsg($client_id, '不能将会话转移给自己哦~');
                return;
            }

            // 带标识符的转移对像客服ID
            $csr_id  = $data['csr'] . '||csr';
            $session = Common::distributionCsr($csr_id, $data['session_user']);

            if ($session) {

                // 将会话发送给新客服
                if (Gateway::isUidOnline($csr_id)) {

                    $message = [
                        'id'           => $session['id'],
                        'session_id'   => $session['id'],
                        'session_user' => $data['session_user'],
                        'last_time'    => Common::formatSessionTime(null),
                        'last_message' => '本会话被客服 ' . $current_csr_info['nickname'] . ' 转移给您',
                        'online'       => 1,
                        'avatar'       => Common::imgSrcFill($session_user_info['avatar'], true),
                        'nickname'     => $session_user_info['nickname'],
                    ];

                    // 查询当前用户发送的未读消息条数
                    $message['unread_msg_count'] = Db::name('kefu_record')
                        ->where('session_id', $message['session_id'])
                        ->where('sender_identity', $session_user_info['source'] == 'csr' ? 0 : 1)
                        ->where('sender_id', $session_user_info['id'])
                        ->where('status', 0)
                        ->count('id');

                    $message['unread_msg_count'] += 1;

                    Gateway::sendToUid($csr_id, json_encode(['msgtype' => 'new_message', 'data' => $message]));
                }

                // 发客服转移消息给用户
                if (Gateway::isUidOnline($data['session_user'])) {
                    Gateway::sendToUid($data['session_user'], json_encode([
                        'msgtype' => 'transfer_done',
                        'data'    => [
                            'csr'      => $csr_id,
                            'nickname' => $session['nickname'],
                        ],
                    ]));
                }

                $res = $session['nickname'];
            } else {
                $res = false;
            }

            Gateway::sendToClient($client_id, json_encode([
                'code'    => 1,
                'msgtype' => 'action_session',
                'data'    => [
                    'res'          => $res,
                    'action'       => 'transfer_done',
                    'session_user' => $data['session_user'],
                ],
            ]));
        } elseif ($data['action'] == 'edit_nickname') {

            if (!isset($data['new_nickname']) || !$data['new_nickname']) {
                common::showMsg($client_id, '新昵称不能为空！');
                return;
            }

            $session_user_info = Common::userInfo($data['session_user']);

            if ($session_user_info) {
                Db::name('kefu_user')->where('id', $session_user_info['id'])->update([
                    'nickname' => $data['new_nickname'],
                ]);

                $new_nickname = $session_user_info['fu_nickname'] ? $session_user_info['fu_nickname'] . '(' . $data['new_nickname'] . ')' : $data['new_nickname'];

                $list_nickname = $session_user_info['fu_nickname'] ? $session_user_info['fu_nickname'] : $data['new_nickname'];

                Gateway::sendToClient($client_id, json_encode([
                    'code'    => 1,
                    'msgtype' => 'action_session',
                    'data'    => [
                        'action'        => 'edit_nickname',
                        'session_user'  => $data['session_user'],
                        'new_nickname'  => $new_nickname,
                        'list_nickname' => $list_nickname,
                    ],
                ]));
            } else {
                common::showMsg($client_id, '昵称修改失败，请重试~');
            }
        }

    }

    /*
     * 输入状态更新
     */
    public function messageInput($client_id, $data)
    {
        if (!isset($data['session_id']) || !isset($data['type']) || !isset($data['session_user'])) {
            return;
        }

        $toMessage = [
            'msgtype' => 'message_input',
            'data'    => [
                'session_id' => $data['session_id'],
                'type'       => $data['type'],
            ]
        ];

        if (Gateway::isUidOnline($data['session_user'])) {
            Gateway::sendToUid($data['session_user'], json_encode($toMessage));
        }
    }

    /*
    * 发送消息
    */
    public function sendMessage($client_id, $data)
    {

        if (!isset($data['session_id'])) {
            common::showMsg($client_id, '发送失败,会话找不到啦！');
            return;
        }

        if (!isset($data['message']) || $data['message'] == '') {
            common::showMsg($client_id, '请输入消息内容！');
            return;
        }

        if (!isset($data['message_type'])) {
            common::showMsg($client_id, '消息类型错误！');
            return;
        }

        if (!isset($_SESSION['user_id'])) {
            common::showMsg($client_id, '无法确认当前您的身份，请刷新重试！');
            return;
        }

        // 复检管理员的登录态
        if ($data['modulename'] == 'admin' && !Common::checkAdmin($data['token'])) {
            common::showMsg($client_id, '无法确认当前您的身份，请刷新重试！');
            return;
        }

        $res = Common::chatRecord($data['session_id'], $data['message'], $data['message_type'], false, isset($data['message_id']) ? $data['message_id'] : false);
        Gateway::sendToClient($client_id, json_encode($res));
    }

    /*轨迹记录*/

    public function trajectory($client_id, $data)
    {
        $page_count = 20; //一次加载20条

        if (!isset($data['page'])) {
            $data['page'] = 1;
        }

        if (!isset($data['session_user'])) {
            common::showMsg($client_id, '用户未找到');
            return;
        }

        // 用户信息
        $user_info = Common::userInfo($data['session_user']);

        if (!$user_info) {
            common::showMsg($client_id, '用户未找到');
            return;
        }

        $user_info['session_user'] = $data['session_user'];

        // 是否屏蔽此人
        $user_info['blacklist'] = Db::name('kefu_blacklist')->where('user_id', $user_info['id'])->value('id');

        $trajectory_count = Db::name('kefu_trajectory')->where('user_id', $user_info['id'])->count('id');

        $page_number = ceil($trajectory_count / $page_count);

        if ($data['page'] == 1) {
            $min = 0;
        } else {
            $min = ($data['page'] - 1) * $page_count;
        }

        $trajectory = Db::name('kefu_trajectory')
            ->where('user_id', $user_info['id'])
            ->limit($min, $page_count)
            ->order('id desc')
            ->select();

        // 最后的一条消息
        $last_message = [
            'last_time'    => Common::formatSessionTime(null),
            'last_message' => '无轨迹记录',
        ];

        if (!$trajectory) {

            $now_ymd                     = date('Y-m-d');
            $trajectory_temp[$now_ymd][] = [
                'id'         => 1,
                'note'       => '无轨迹记录',
                'log_type'   => 7,
                'createtime' => Common::formatTime(null),
            ];

        } else {

            if ($data['page'] == 1) {
                $last_message = [
                    'last_time'    => Common::formatSessionTime($trajectory[0]['createtime']),
                    'last_message' => $trajectory[0]['log_type'] == 0 ? '访问 ' . $trajectory[0]['url'] : $trajectory[0]['note'],
                ];
                $trajectory   = array_reverse($trajectory, false);
            }

            // 按天分组
            $trajectory_temp = [];

            foreach ($trajectory as $key => $value) {

                $createtime          = date('Y-m-d', $value['createtime']);
                $value['createtime'] = date('H:i', $value['createtime']);

                $trajectory_temp[$createtime][] = $value;
            }
        }

        Gateway::sendToClient($client_id, json_encode([
            'msgtype' => 'trajectory',
            'code'    => 1,
            'data'    => [
                'trajectory'   => $trajectory_temp,
                'user_info'    => $user_info,
                'last_message' => $last_message,
                'next_page'    => ($data['page'] >= $page_number) ? 'done' : $data['page'] + 1,
                'page'         => $data['page'],
            ],
        ]));
    }

    /*搜索用户*/

    public function searchUser($client_id, $keywords)
    {

        $csr_info = Common::userInfo($_SESSION['user_id']);

        if ($csr_info['source'] != 'csr') {
            return;
        }

        // 读取会话列表
        $session = Db::name('kefu_session')
            ->alias('s')
            ->field('s.*,CONCAT(u.id,"||user") as session_user,u.user_id as fu_user_id,u.avatar,u.nickname,fu.avatar as fu_avatar,fu.nickname as fu_nickname')
            ->join('kefu_user u', 'u.id=s.user_id')
            ->join('user fu', 'u.user_id=fu.id', 'LEFT')
            ->where('s.csr_id', $csr_info['id'])
            ->where('s.deletetime', null)
            ->limit(40)
            ->order('s.createtime desc')
            ->select();

        foreach ($session as $key => $value) {

            // 最后一条聊天记录
            $last_message = Db::name('kefu_record')
                ->where('session_id', $value['id'])
                ->order('createtime desc')
                ->find();

            $session[$key]['last_message'] = Common::formatMessage($last_message);
            $session[$key]['last_time']    = Common::formatSessionTime($last_message['createtime']);
            $session[$key]['avatar']       = $value['fu_avatar'] ? $value['fu_avatar'] : $session[$key]['avatar'];
            $session[$key]['nickname']     = $value['fu_nickname'] ? $value['fu_nickname'] . '(' . $session[$key]['nickname'] . ')' : $session[$key]['nickname'];
            $session[$key]['avatar']       = Common::imgSrcFill($value['avatar'], true);
            $session[$key]['online']       = Gateway::isUidOnline($value['session_user']);
        }

        $invitation          = Gateway::getAllUidList();
        $invitation_user_ids = [];

        foreach ($invitation as $key => $value) {
            $invitation_user_id = explode('||', $value);

            if (isset($invitation_user_id[1]) && $invitation_user_id[1] != 'csr' && (int)$invitation_user_id[0] > 0) {
                $invitation_user_ids[] = (int)$invitation_user_id[0];
            }
        }

        $invitation_user_ids = implode(',', $invitation_user_ids);
        $invitation          = Db::name('kefu_user')
            ->alias('u')
            ->field('u.id,u.avatar,u.nickname,u.createtime,s.id as sid')
            ->join('kefu_session s', 's.user_id=u.id', 'LEFT')
            ->whereIn('u.id', $invitation_user_ids)
            ->where('s.id', null)
            ->where('u.nickname', 'like', '%' . $keywords . '%')
            ->select();

        foreach ($invitation as $key => $value) {
            $invitation[$key]['id']               = 'invitation||' . $value['id'];
            $invitation[$key]['avatar']           = Common::imgSrcFill($value['avatar'], true);
            $invitation[$key]['online']           = 1;
            $invitation[$key]['unread_msg_count'] = 0;
            $invitation[$key]['last_message']     = '';
            $invitation[$key]['session_user']     = $value['id'] . '||user';
            $invitation[$key]['last_time']        = Common::formatSessionTime($value['createtime']);
        }

        $user_list      = array_merge($invitation, $session);
        $user_list_temp = [];
        foreach ($user_list as $key => $value) {
            if (strstr($value['nickname'], $keywords)) {
                $user_list_temp[] = $value;
            }
        }
        unset($user_list);

        Gateway::sendToClient($client_id, json_encode(['msgtype' => 'search_user', 'data' => $user_list_temp]));
    }

    /*拉黑名单*/
    public function blacklist($client_id, $data)
    {
        if (!isset($data['session_user']) || !$data['session_user']) {
            common::showMsg($client_id, '用户找不到啦！');
            return;
        }

        $user_info = Common::userInfo($data['session_user']);

        if (!$user_info) {
            common::showMsg($client_id, '用户找不到啦！');
            return;
        }

        $blacklist = Db::name('kefu_blacklist')->where('user_id', $user_info['id'])->value('id');

        if ($blacklist) {

            if (Db::name('kefu_blacklist')->where('user_id', $user_info['id'])->delete()) {
                Gateway::sendToClient($client_id, json_encode([
                    'code'    => 1,
                    'msgtype' => 'blacklist',
                    'data'    => [
                        'action'       => 'del',
                        'session_user' => $data['session_user'],
                    ],
                ]));
            } else {
                common::showMsg($client_id, '取消拉黑失败，请重试！');
                return;
            }
            return;
        }

        $csr_info  = Common::userInfo($_SESSION['user_id']);
        $blacklist = [
            'user_id'    => $user_info['id'],
            'admin_id'   => $csr_info['id'],
            'createtime' => time(),
        ];

        if (Db::name('kefu_blacklist')->insert($blacklist)) {

            $black_client_id = Gateway::getClientIdByUid($data['session_user']);
            foreach ($black_client_id as $key => $value) {
                Gateway::sendToClient($value, json_encode([
                    'code'    => 0,
                    'msgtype' => 'clear',
                ]));
            }

            Gateway::sendToClient($client_id, json_encode([
                'code'    => 1,
                'msgtype' => 'blacklist',
                'data'    => [
                    'action'          => 'add',
                    'session_user'    => $data['session_user'],
                    'black_client_id' => $black_client_id,
                ],
            ]));
        } else {
            common::showMsg($client_id, '拉黑失败，请重试！');
            return;
        }
    }
}
