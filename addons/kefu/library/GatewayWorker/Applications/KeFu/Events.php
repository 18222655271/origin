<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */

namespace addons\kefu\library\GatewayWorker\Applications\KeFu;

//declare(ticks=1);

use addons\kefu\library\Common;
use GatewayWorker\Lib\Gateway;
use Workerman\Lib\Timer;
use think\Db;

/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events
{
    /**
     * WebSocket 链接成功
     *
     * @param int $client_id data
     * @param $[data] [websocket握手时的http头数据，包含get、server等变量]
     */
    public static function onWebSocketConnect($client_id, $data)
    {

        // 安全检查
        array_walk_recursive($data, ['addons\kefu\library\Common', 'checkVariable']);

        $now_time                     = time();
        $initialize_data              = [];
        $initialize_data['chat_name'] = Db::name('kefu_config')->where('name', 'chat_name')->value('value');
        $agreement                    = (stripos($data['server']['HTTP_ORIGIN'], 'https://') === false) ? 'http://' : 'https://';
        $_SESSION['cdn_url']          = $agreement . $data['server']['SERVER_NAME']; //设置服务器域名
        $kefu_config                  = get_addon_config('kefu');

        $upload = \app\common\model\Config::upload();
        // 上传信息配置后
        \think\Hook::listen("upload_config_init", $upload);
        $_SESSION['cdn_url'] = $upload['cdnurl'] ? $upload['cdnurl'] : $_SESSION['cdn_url'];


        // 获取连接人信息
        $token_info = false;

        if (!isset($data['get']['modulename'])) {

            Gateway::sendToClient($client_id, json_encode([
                'code'    => 0,
                'msgtype' => 'clear',
                'msg'     => $initialize_data['chat_name'] . ' 模块未知',
            ]));
            return;
        }

        if ($data['get']['modulename'] == 'admin' && isset($data['get']['token'])) {
            // 验证管理员身份
            $token_info = Common::checkAdmin($data['get']['token']);

            // 设置定时器，定时检测管理员身份是否过期
            $_SESSION['auth_timer_id'] = Timer::add(30, function ($client_id, $token) {
                $token_info = Common::checkAdmin($token);
                if (!$token_info) {
                    Gateway::closeClient($client_id);
                }
            }, [$client_id, $data['get']['token']]);

        } elseif ($data['get']['modulename'] != 'admin' && isset($data['get']['token'])) {
            // 验证FA用户身份
            $user_id = Common::checkFaUser($data['get']['token']);
            if ($user_id) {
                // 验证KeFu用户身份
                $token_info = Common::checkKefuUser('', $user_id);
                if ($token_info) {
                    // 设置定时器，定时检测用户身份是否过期
                    $_SESSION['auth_timer_id'] = Timer::add(60, function ($client_id, $token) {
                        $user_id = Common::checkFaUser($token);
                        if (!$user_id) {
                            Gateway::closeClient($client_id);
                        }
                    }, [$client_id, $data['get']['token']]);
                }
            }

        }

        if ($data['get']['modulename'] != 'admin' && isset($data['get']['kefu_tourists_token']) && !$token_info) {
            // 验证KeFu用户身份
            $token_info = Common::checkKefuUser($data['get']['kefu_tourists_token'], 0);
        }

        if ($token_info) {

            if (isset($token_info['token'])) {
                unset($token_info['token']);
            }

            if (isset($token_info['blacklist']) && $token_info['blacklist']) {
                Gateway::sendToClient($client_id, json_encode([
                    'code'    => 0,
                    'msgtype' => 'clear',
                    'msg'     => $initialize_data['chat_name'] . ' 黑名单用户！',
                ]));
                return;
            }

            Gateway::bindUid($client_id, $token_info['user_id']);
            $_SESSION['user_id'] = $token_info['user_id'];
        } else {

            Gateway::sendToClient($client_id, json_encode([
                'code'    => 0,
                'msgtype' => 'clear',
                'msg'     => $initialize_data['chat_name'] . ' 无法识别链接用户身份，请重新登录！',
            ]));
            return;
        }

        if ($data['get']['modulename'] == 'admin') {

            // 读取会话列表
            $session = Db::name('kefu_session')
                ->alias('s')
                ->field('s.*,CONCAT(u.id,"||user") as session_user,u.user_id as fu_user_id,u.avatar,u.nickname,u.wechat_openid,fu.avatar as fu_avatar,fu.nickname as fu_nickname')
                ->join('kefu_user u', 'u.id=s.user_id')
                ->join('user fu', 'u.user_id=fu.id', 'LEFT')
                ->where('s.csr_id', $token_info['id'])
                ->where('s.deletetime', null)
                ->limit(40)
                ->order('s.createtime desc')
                ->select();

            $session = array_reverse($session, false); // 会话分组时数组键将被逆转,最终给到前台的则是可以直接for in的数组

            // 会话列表分组 在线的且上次消息时间在最近的-放入对话中 不在线的或者上次消息时间较久的放入最近沟通
            $session_temp = [];
            foreach ($session as $key => $value) {

                // 最后一条聊天记录
                $last_message = Db::name('kefu_record')
                    ->where('session_id', $value['id'])
                    ->order('createtime desc')
                    ->find();

                $value['last_message'] = Common::formatMessage($last_message);
                $value['last_time']    = Common::formatSessionTime(isset($last_message['createtime']) ? $last_message['createtime'] : null);

                $value['online']   = $value['wechat_openid'] ? 1 : Gateway::isUidOnline($value['session_user']);
                $value['avatar']   = $value['fu_avatar'] ? $value['fu_avatar'] : $value['avatar'];
                $value['nickname'] = $value['fu_nickname'] ? $value['fu_nickname'] : $value['nickname'];
                $value['avatar']   = Common::imgSrcFill($value['avatar'], true);

                // 用户发来的未读消息数
                $value['unread_msg_count'] = Db::name('kefu_record')
                    ->where('session_id', $value['id'])
                    ->where('sender_identity', 1)
                    ->where('sender_id', $value['user_id'])
                    ->where('status', 0)
                    ->count('id');

                $last_time = isset($last_message['createtime']) ? $last_message['createtime'] : $value['createtime'];

                $dialogue_time = $value['wechat_openid'] ? 600 : 43200; // 这个时间内的会话计入会话中

                if ($value['online'] || ($now_time - $last_time < $dialogue_time) || $value['unread_msg_count'] > 0) {
                    $session_temp['dialogue'][] = $value;
                } else {
                    $session_temp['recently'][] = $value;
                }
            }

            // 客服上线
            $reception_count = isset($session_temp['dialogue']) ? count($session_temp['dialogue']) : false;
            if ($reception_count) {
                Db::name('kefu_csr_config')->where('admin_id', $token_info['id'])->update([
                    'reception_count' => $reception_count,
                ]);
            }

            // 获取访问中(邀请中)的用户->查询对应的用户信息
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
                ->field('u.id,u.avatar,u.nickname,u.createtime,s.id as sid,fu.avatar as fu_avatar,fu.nickname as fu_nickname')
                ->join('user fu', 'u.user_id=fu.id', 'LEFT')
                ->join('kefu_session s', 's.user_id=u.id', 'LEFT')
                ->whereIn('u.id', $invitation_user_ids)
                ->where('s.id', null)
                ->select();

            foreach ($invitation as $key => $value) {

                /*$trajectory = Db::name('kefu_trajectory')
                ->where('user_id', $value['id'])
                ->order('id desc')
                ->find();*/

                $invitation[$key]['id']               = 'invitation||' . $value['id'];
                $invitation[$key]['avatar']           = $value['fu_avatar'] ? $value['fu_avatar'] : $value['avatar'];
                $invitation[$key]['avatar']           = Common::imgSrcFill($invitation[$key]['avatar'], true);
                $invitation[$key]['nickname']         = $value['fu_nickname'] ? $value['fu_nickname'] : $value['nickname'];
                $invitation[$key]['online']           = 1;
                $invitation[$key]['unread_msg_count'] = 0;
                $invitation[$key]['last_message']     = '';
                $invitation[$key]['session_user']     = $value['id'] . '||user';
                $invitation[$key]['last_time']        = Common::formatSessionTime($value['createtime']);
            }

            $session_temp['invitation'] = $invitation;

            $initialize_data['session'] = $session_temp;

            // 获取状态
            $token_info['status_text'] = Common::csrStatus(null);
            $tourists                  = 'not';

        } else {

            if (!Db::name('kefu_session')->where('user_id', $token_info['id'])->value('id')) {

                // 无客服游客-供前台建立会话
                $avatar   = $token_info['fu_avatar'] ? $token_info['fu_avatar'] : $token_info['avatar'];
                $tourists = [
                    'id'               => 'invitation||' . $token_info['id'],
                    'avatar'           => Common::imgSrcFill($avatar, true),
                    'nickname'         => $token_info['fu_nickname'] ? $token_info['fu_nickname'] : $token_info['nickname'],
                    'online'           => 1,
                    'unread_msg_count' => 0,
                    'session_user'     => $token_info['id'] . '||user',
                    'last_message'     => '',
                    'last_time'        => Common::formatSessionTime($token_info['createtime']),
                ];
            } else {
                $tourists = 'not';
            }
        }

        $initialize_data['modulename'] = $data['get']['modulename'];
        $initialize_data['user_info']  = $token_info;
        $initialize_data['new_msg']    = Common::getUnreadMessages($_SESSION['user_id'], true);

        // 向当前client_id发送数据
        Gateway::sendToClient($client_id, json_encode(['msgtype' => 'initialize', 'data' => $initialize_data]));

        // 向所有人发送
        Gateway::sendToAll(json_encode([
            'msgtype'    => 'online',
            'user_id'    => $_SESSION['user_id'],
            'user_name'  => isset($token_info['fu_nickname']) ? ($token_info['fu_nickname'] . '(' . $token_info['nickname'] . ')') : $token_info['nickname'],
            'tourists'   => $tourists,
            'modulename' => $data['get']['modulename'],
        ]));
    }

    /**
     * 当客户端发来消息时触发
     * @param int $client_id 连接id
     * @param mixed $message 具体消息
     */
    public static function onMessage($client_id, $message)
    {
        $chat_name = Db::name('kefu_config')->where('name', 'chat_name')->value('value');

        // 分发到控制器
        $data = json_decode($message, true);

        // 安全检查
        array_walk_recursive($data, ['addons\kefu\library\Common', 'checkVariable']);

        if (!is_array($data) || !isset($data['c']) || !isset($data['a'])) {

            common::showMsg($client_id, $chat_name . ' 错误的请求！');
            return;
        }

        if ($data['c'] == 'clear') {
            Gateway::closeClient($client_id);
            return '';
        }

        $filename = __DIR__ . '/controller/' . $data['c'] . '.php'; //载入文件类似/controller/index.php

        if (file_exists($filename)) {

            require_once $filename;

            /*
            检查要访问的类是否存在
             */
            if (!class_exists($data['c'], false)) {

                common::showMsg($client_id, $chat_name . ' 您访问的控制器不存在！');
                return;
            }
        } else {

            common::showMsg($client_id, $chat_name . ' 您访问的文件并存在！');
            return;
        }

        $o = new $data['c'](); // 新建对象

        if (!method_exists($o, $data['a'])) {

            common::showMsg($client_id, $chat_name . ' 您访问的方法并存在！');
            return;
        }

        $data['data'] = isset($data['data']) ? $data['data'] : '';

        call_user_func_array([$o, $data['a']], [$client_id, $data['data']]); //调用对象$o($c)里的方法$a
    }

    /**
     * 当用户断开连接时触发
     * @param int $client_id 连接id
     */
    public static function onClose($client_id)
    {
        if (isset($_SESSION['auth_timer_id'])) {
            Timer::del($_SESSION['auth_timer_id']);
        }

        // 向所有人发送
        if (isset($_SESSION['user_id'])) {

            // 此user_id下还有其他链接
            try {
                if (Gateway::getClientIdByUid($_SESSION['user_id'])) {
                    return;
                }
            } catch (\Exception $e) {

            }

            $user_info = Common::userInfo($_SESSION['user_id']);

            if ($user_info['source'] == 'user') {

                $csr_id = Db::name('kefu_session')->where('user_id', $user_info['id'])->value('csr_id');

                if ($csr_id) {

                    $reception_count = Db::name('kefu_csr_config')
                        ->where('admin_id', $csr_id)
                        ->value('reception_count');

                    if ($reception_count > 0) {
                        Db::name('kefu_csr_config')->where('admin_id', $csr_id)->setDec('reception_count');
                    }

                }

            } elseif ($user_info['source'] == 'csr' && $user_info['status'] == 3) {
                // 客服保持在线
                $keep_alive = Db::name('kefu_csr_config')->where('admin_id', $user_info['id'])->value('keep_alive');
                if ($keep_alive) {
                    return;
                }

                Common::csrStatus(0);

                // 客服下线
                Db::name('kefu_csr_config')->where('admin_id', $user_info['id'])->update([
                    'reception_count' => 0,
                ]);
            }

            Gateway::sendToAll(json_encode([
                'msgtype' => 'offline',
                'user_id' => $_SESSION['user_id'],
            ]));

        }

        Db::clear();
    }

}
