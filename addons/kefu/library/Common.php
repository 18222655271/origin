<?php

namespace addons\kefu\library;

use GatewayWorker\Lib\Gateway;
use think\Db;

/**
 *
 */
class Common
{

    public function __construct()
    {

    }

    /**
     * 发送钉钉通知
     * @param  [type] $robot 机器人列表，一行一个
     * @param  [type] $title 通知标题
     * @param  [type] $content markdown通知内容
     * @param  [type] $isAtAll 是否at所有人
     * @return bool
     */
    public static function dingNotice($robot, $title, $content, $isAtAll)
    {
        $dinghorn   = get_addon_info('dinghorn');
        $is_success = false;

        if ($dinghorn && $dinghorn['state'] == 1) {

            $robot = explode(PHP_EOL, $robot);
            foreach ($robot as $key => $value) {

                $value = (int)trim($value);
                if ($value) {
                    $robot[$key] = $value;
                } else {
                    unset($robot[$key]);
                }
            }

            $robot = implode(',', $robot);
            $robot = Db::name('dinghorn_robot')->whereIn('id', $robot)->select();

            $dataObj = [
                'msgtype'  => 'markdown',
                'markdown' => [
                    'title' => $title,
                    'text'  => $content,
                ],
                'at'       => [
                    'atMobiles' => [],
                    'isAtAll'   => $isAtAll,
                ]
            ];

            $dinghorn = new \addons\dinghorn\library\DinghornLib();
            foreach ($robot as $key => $value) {
                $sign = isset($value['sign']) ? $value['sign'] : false;
                $res  = $dinghorn->msgSend($value['access_token'], $dataObj, $sign);
                if ($res['errcode'] != 0) {
                    $is_success = false;
                }
            }

        }

        return $is_success;
    }

    /**
     * 从其他模块推送消息到会话服务-比如小程序的客服消息推到客服处
     * @param int $session_id 会话ID
     * @param string $message 消息内容
     * @param int $message_type 消息类型
     * @param string $sender 小程序用户带标识的用户ID
     * @return bool
     */
    public static function socketMessage($session_id, $message, $message_type, $sender)
    {
        $kefu_config = get_addon_config('kefu');

        $connection = @stream_socket_client('tcp://127.0.0.1:' . ($kefu_config['register_port'] + 100));

        if (!$connection) {
            return false;
        }

        $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : false;

        $send_text = [
            'c'    => 'Message',
            'a'    => 'pushMessage',
            'data' => [
                'session_id'   => $session_id,
                'message'      => $message,
                'message_type' => $message_type,
                'sender'       => $sender,
                'origin'       => $http_origin,
            ],
        ];

        $send_text = json_encode($send_text) . "\n";

        if (fwrite($connection, $send_text) !== false) {
            fclose($connection);

            // \think\Log::record('发送的消息内容' . $send_text,'notice');
            return true;
        }

        return false;
    }

    /**
     * 用户token加密
     * @param string $token 待加密的token
     */
    public static function getEncryptedToken($token)
    {
        $token_config = \think\Config::get('token');

        $config = [
            // 缓存前缀
            'key'      => $token_config['key'],
            // 加密方式
            'hashalgo' => $token_config['hashalgo'],
        ];

        return hash_hmac($config['hashalgo'], $token, $config['key']);
    }

    /**
     * 检查管理员身份
     * @param string admin_token 管理员cookie信息
     * @param string admin_id  管理员ID直接登录
     * @return array
     */
    public static function checkAdmin($admin_token, $admin_id = 0)
    {

        if ($admin_id) {

            $admin = Db::name('admin')->field(['password', 'salt'], true)->where('id', $admin_id)->find();
            if (!$admin) {
                return false;
            }
        } else {
            list($id, $keeptime, $expiretime, $key) = explode('|', $admin_token);

            if ($id && $keeptime && $expiretime && $key && $expiretime > time()) {

                $admin = Db::name('admin')->field(['password', 'salt'], true)->where('id', $id)->find();

                if (!$admin || !$admin['token']) {
                    return false;
                }

                // 检查token是否有变更
                $sign = $id . 'kefu_admin_sign_additional';
                if ($key != md5(md5($sign) . md5($keeptime) . md5($expiretime) . $admin['token'])) {
                    return false;
                }

            } else {
                return false;
            }
        }

        // 检查是否是客服账号
        $csr_config = Db::name('kefu_csr_config')->where('admin_id', $admin['id'])->find();

        if ($csr_config) {

            if ($csr_config['status'] == 0) {
                // 修改状态为在线
                Db::name('kefu_csr_config')->where('admin_id', $admin['id'])->update(['status' => 3]);

                $admin['status'] = 3;
            } else {
                $admin['status'] = $csr_config['status'];
            }

            $admin['user_id'] = $admin['id'] . '||csr';
            $admin['source']  = 'csr';
            return $admin;
        }

        return false;
    }

    /**
     * 检查FastAdmin用户token
     * @param string token 用户的token信息
     * @return int 用户ID
     */
    public static function checkFaUser($token)
    {
        $cookie_httponly = config('cookie.httponly');
        if (!$cookie_httponly) {
            $user_id = Db::name('user_token')->where('token', Common::getEncryptedToken($token))->value('user_id');
        } else {
            list($id, $key) = explode('|', $token);
            $user_token_list = Db::name('user_token')
                ->where('user_id', $id)
                ->where('expiretime', '>', time())
                ->select();
            foreach ($user_token_list as $user_token) {
                $sign     = $user_token['token'] . 'kefu_user_sign_additional';
                $user_key = md5(md5($id) . md5($sign));
                if ($user_key == $key) {
                    $user_id = $id;
                    break;
                } else {
                    $user_id = false;
                }
            }

        }

        return $user_id;
    }

    /**
     * 检查游客，获取、绑定用户
     * @param string kefu_user_cookie 用户的cookie信息
     * @param int user_id 用户ID
     * @return array
     */
    public static function checkKefuUser($kefu_user_cookie, $user_id = 0)
    {
        // 用户轨迹
        $trajectory = [
            'csr_id' => 0,
            'note'   => '',
        ];

        if ($user_id > 0) {

            $kefu_user = Db::name('kefu_user')
                ->alias('u')
                ->field('u.*,fu.avatar as fu_avatar,fu.nickname as fu_nickname')
                ->join('user fu', 'u.user_id=fu.id', 'LEFT')
                ->where('u.user_id', $user_id)
                ->find();

            // 轨迹数据
            $csr_id = Db::name('kefu_session')
                ->alias('s')
                ->field('s.*,a.id as admin_id,a.nickname')
                ->join('admin a', 's.csr_id=a.id')
                ->where('s.user_id', $user_id)
                ->where('s.deletetime', null)
                ->value('csr_id');

            $trajectory['csr_id'] = $csr_id ? $csr_id : 0;

        } else {

            $_SESSION['is_tourists'] = true;
        }

        if ($kefu_user_cookie && (!isset($kefu_user) || !$kefu_user)) {

            // ios 网络传输特殊符号兼容
            if (strstr($kefu_user_cookie, '~') !== false) {
                $kefu_user_cookie = str_replace('~', '|', $kefu_user_cookie);
            }

            list($id, $keeptime, $expiretime, $key) = explode('|', $kefu_user_cookie);
            if ($id && $keeptime && $expiretime && $key && $expiretime > time()) {

                $kefu_user = Db::name('kefu_user')
                    ->alias('u')
                    ->field('u.*,fu.id as fu_id,fu.avatar as fu_avatar,fu.nickname as fu_nickname')
                    ->join('user fu', 'u.user_id=fu.id', 'LEFT')
                    ->where('u.id', $id)
                    ->find();

                if (!$kefu_user || !$kefu_user['token']) {
                    return false;
                }

                //token有变更
                if ($key != md5(md5($id) . md5($keeptime) . md5($expiretime) . $kefu_user['token'])) {
                    return false;
                }

                // 轨迹数据
                $csr_id = Db::name('kefu_session')
                    ->alias('s')
                    ->field('s.*,a.id as admin_id,a.nickname')
                    ->join('admin a', 's.csr_id=a.id')
                    ->where('s.user_id', $id)
                    ->where('s.deletetime', null)
                    ->value('csr_id');

                $trajectory['csr_id'] = $csr_id ? $csr_id : 0;

                if ($user_id > 0) {

                    // 绑定用户
                    if (!$kefu_user['fu_id']) {
                        $user_info = Db::name('user')->where('id', $user_id)->find();
                        if ($user_info) {
                            Db::name('kefu_user')->where('id', $id)->update([
                                'user_id' => $user_id,
                                // 'avatar'  => $user_info['avatar'], 保留游客的原始数据
                                // 'nickname'=> $user_info['nickname']
                            ]);

                            $kefu_user['avatar']   = $user_info['avatar'];
                            $kefu_user['nickname'] = $user_info['nickname'];

                            $trajectory['note'] = '登录为会员:' . $user_info['nickname'] . '(ID:' . $user_id . ')';
                        }
                    } else {

                        // 当前的游客用户已经绑定了会员
                        // 已有会员登陆，但无对应客服系统用户
                        // 可能是同一设备换号登录(建立新的游客用户并绑定给他)
                        $tourists = self::createTourists();
                        if ($tourists) {

                            Db::name('kefu_user')
                                ->where('id', $tourists['kefu_user_id'])
                                ->update(['user_id' => $user_id]);
                            self::checkKefuUser($tourists['kefu_user_cookie'], $user_id);
                        } else {
                            return false;
                        }
                    }
                }

            } else {
                return false;
            }
        }

        if (!isset($kefu_user)) {
            return false;
        }

        // 检查黑名单
        $kefu_user['blacklist'] = Db::name('kefu_blacklist')->where('user_id', $kefu_user['id'])->value('id');

        $kefu_user['trajectory'] = $trajectory;

        $kefu_user['user_id'] = $kefu_user['id'] . '||user';
        $kefu_user['source']  = 'user';
        unset($kefu_user['token']);
        return $kefu_user;
    }

    /**
     * 创建一个游客
     * @return [type] [description]
     */
    public static function createTourists($referrer = '')
    {
        $tourists_max_id = Db::name('kefu_user')->max('id');
        $token           = \fast\Random::uuid();
        $kefu_user       = [
            'avatar'     => '',
            'nickname'   => '游客 ' . $tourists_max_id,
            'referrer'   => $referrer,
            'token'      => $token,
            'createtime' => time(),
        ];

        if (Db::name('kefu_user')->insert($kefu_user)) {
            $kefu_user_id = Db::name('kefu_user')->getLastInsID();
            Db::name('kefu_user')->where('id', $kefu_user_id)->update(['nickname' => '游客 ' . $kefu_user_id]);
            $keeptime         = 864000;
            $expiretime       = time() + $keeptime;
            $key              = md5(md5($kefu_user_id) . md5($keeptime) . md5($expiretime) . $token);
            $kefu_user_cookie = [$kefu_user_id, $keeptime, $expiretime, $key];

            return [
                'kefu_user_cookie' => implode('|', $kefu_user_cookie),
                'kefu_user_id'     => $kefu_user_id,
            ];
        } else {
            return false;
        }
    }

    /**
     * 获取合适的客服代表
     * @return string 带标识客服代表ID
     */
    public static function getAppropriateCsr()
    {
        $csr_distribution = Db::name('kefu_config')->where('name', 'csr_distribution')->value('value');
        if ($csr_distribution == 0) {

            // 拿到当前接待量最少的客服
            $reception_count = Db::name('kefu_csr_config')->where('status', 3)->min('reception_count');
            $csr_list        = Db::name('kefu_csr_config')
                ->where('status', 3)
                ->where('reception_count', $reception_count)
                ->select();
            if ($csr_list && count($csr_list) == 1) {
                $csr = $csr_list[0];
            }
        } elseif ($csr_distribution == 1) {

            // 根据接待上限和当前接待量，分配给最能接待的客服
            $csr = Db::name('kefu_csr_config')
                ->field('id,admin_id,CAST(ceiling as signed) - CAST(reception_count as signed) as weight')
                ->where('status', 3)
                ->order('weight desc')
                ->find();
        }

        if ($csr_distribution == 2 || !$csr) {
            // 分配给最久未进行接待的客服
            $csr = Db::name('kefu_csr_config')->where('status', 3)->order('last_reception_time asc')->find();
        }

        if ($csr) {
            return $csr['admin_id'] . '||csr';
        } else {
            return false;
        }
    }

    /**
     * 分配/转移客服
     * @param int csr 客服代表带标识符ID
     * @param int user 带标识符用户ID
     * @return array 新的会话信息
     */
    public static function distributionCsr($csr, $user = false)
    {
        if (!$user) {
            $user = $_SESSION['user_id'];
        }

        $user_info = self::userInfo($user);
        if ($user_info['source'] == 'csr') {
            return false;
        }

        $csr_info = self::userInfo($csr);
        if ($csr_info['source'] != 'csr') {
            return false;
        }

        $welcome_msg = $csr_info['welcome_msg'] ? $csr_info['welcome_msg'] : Db::name('kefu_config')
            ->where('name', 'new_user_msg')
            ->value('value');

        // 检查是否已有客服
        $session = Db::name('kefu_session')
            ->alias('s')
            ->field('s.*,a.id as admin_id,a.nickname')
            ->join('admin a', 's.csr_id=a.id')
            ->where('s.user_id', $user_info['id'])
            ->where('s.deletetime', null)
            ->find();

        if ($session) {
            // 切换客服
            Db::startTrans();
            try {

                $note = '客服代表已由 ' . $session['nickname'] . ' 转为 ' . $csr_info['nickname'];

                // 记录轨迹
                $trajectory = [
                    'user_id'    => $user_info['id'],
                    'csr_id'     => $csr_info['id'],
                    'log_type'   => 8,
                    'note'       => $note,
                    'url'        => '',
                    'referrer'   => '',
                    'createtime' => time(),
                ];
                Db::name('kefu_trajectory')->insert($trajectory);

                self::chatRecord($session['id'], $note, 3, $csr);

                Db::name('kefu_session')->where('id', $session['id'])->update([
                    'csr_id' => $csr_info['id'],
                ]);

                if ($welcome_msg) {
                    self::chatRecord($session['id'], $welcome_msg, 0, $csr);
                }

                // 插入接待记录,用于数据统计
                $reception_log = [
                    'csr_id'     => $csr_info['id'],
                    'user_id'    => $user_info['id'],
                    'createtime' => time(),
                ];
                Db::name('kefu_reception_log')->insert($reception_log);

                Db::name('kefu_csr_config')->where('admin_id', $csr_info['id'])->inc('reception_count')->update([
                    'last_reception_time' => time(),
                ]);

                $reception_count = Db::name('kefu_csr_config')
                    ->where('admin_id', $session['admin_id'])
                    ->value('reception_count');

                if ($reception_count) {
                    Db::name('kefu_csr_config')->where('admin_id', $session['admin_id'])->setDec('reception_count');
                }

                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                return false;
            }

            $session['csr_id']   = $session['admin_id'] = $csr_info['id'];
            $session['nickname'] = $csr_info['nickname'];
            return $session;
        } else {
            // 分配客服
            $kefu_session = [
                'user_id'    => $user_info['id'],
                'csr_id'     => $csr_info['id'],
                'createtime' => time(),
            ];

            Db::startTrans();
            try {

                $trajectory = [
                    'user_id'    => $user_info['id'],
                    'csr_id'     => $csr_info['id'],
                    'log_type'   => 2,
                    'note'       => '客服代表 ' . $csr_info['nickname'],
                    'url'        => '',
                    'referrer'   => '',
                    'createtime' => time(),
                ];
                Db::name('kefu_trajectory')->insert($trajectory);

                Db::name('kefu_session')->insert($kefu_session);
                $session_id = Db::name('kefu_session')->getLastInsID();

                // 插入接待记录,用于数据统计
                $reception_log = [
                    'csr_id'     => $csr_info['id'],
                    'user_id'    => $user_info['id'],
                    'createtime' => time(),
                ];
                Db::name('kefu_reception_log')->insert($reception_log);

                // 发送欢迎消息
                if ($welcome_msg) {
                    self::chatRecord($session_id, $welcome_msg, 0, $csr);
                }

                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                return false;
            }

            $session = Db::name('kefu_session')
                ->alias('s')
                ->field('s.*,a.id as admin_id,a.nickname')
                ->join('admin a', 's.csr_id=a.id')
                ->where('s.user_id', $user_info['id'])
                ->where('s.deletetime', null)
                ->find();

            return $session;
        }
    }

    /**
     * 获取用户信息
     * @param string user 带标识符的用户id
     * @return array
     */
    public static function userInfo($user)
    {
        $user = explode('||', $user);

        if (isset($user[1])) {

            if ($user[1] == 'user') {

                $user_info = Db::name('kefu_user')
                    ->alias('u')
                    ->field('u.*,fu.avatar as fu_avatar,fu.nickname as fu_nickname')
                    ->join('user fu', 'u.user_id=fu.id', 'LEFT')
                    ->where('u.id', $user[0])
                    ->find();

                if ($user_info) {

                    $user_info['avatar'] = $user_info['fu_avatar'] ? $user_info['fu_avatar'] : $user_info['avatar'];

                    if ($user_info['fu_nickname']) {
                        $user_info['nickname_origin'] = $user_info['nickname'];
                        $user_info['nickname']        = $user_info['fu_nickname'] . '(' . $user_info['nickname'] . ')';
                    } else {
                        $user_info['nickname_origin'] = $user_info['nickname'];
                    }
                }

                $user_info['session_type'] = 1;
                $user_info['source']       = 'user';
            } elseif ($user[1] == 'csr') {
                $user_info = Db::name('admin')->field('id,nickname,avatar')->where('id', $user[0])->find();

                $user_info['session_type'] = 2;
                $user_info['source']       = 'csr';

                $kefu_csr                 = Db::name('kefu_csr_config')
                    ->field('id,welcome_msg,status')
                    ->where('admin_id', $user_info['id'])
                    ->find();
                $user_info['welcome_msg'] = $kefu_csr['welcome_msg'];
                $user_info['status']      = $kefu_csr['status'];
            }

            if (!$user_info || !isset($user_info['id'])) {
                $user_info['id']           = $user[0];
                $user_info['source']       = 'unknown';
                $user_info['nickname']     = '未知用户' . $user[0];
                $user_info['avatar']       = false;
                $user_info['session_type'] = 0;
            }

            $user_info['avatar'] = self::imgSrcFill($user_info['avatar'], true);
        } else {
            $user_info['id']           = $user[0];
            $user_info['source']       = 'unknown';
            $user_info['nickname']     = '未知用户' . $user[0];
            $user_info['avatar']       = self::imgSrcFill(false, true);
            $user_info['session_type'] = 0;
        }

        return $user_info;
    }

    /**
     * 获取图片的完整地址
     * @param string src 待处理的图片
     * @return string
     */
    public static function imgSrcFill($src, $avatar = false)
    {
        if (preg_match('/^http(s)?:\/\//', $src)) {
            return $src;
        }
        // $_SESSION['cdn_url'] 存在则代表当前为workerman环境
        $domain = isset($_SESSION['cdn_url']) ? $_SESSION['cdn_url'] : cdnurl('', true);
        return $src ? $domain . $src : $domain . ($avatar ? '/assets/img/avatar.png' : '/assets/img/blank.gif');
    }

    /**
     * 写入聊天记录/系统消息
     * @param int session_id 会话ID
     * @param string message 消息内容
     * @param string message_type 消息类型
     * @param string sender 带标识的发送人
     * @param string message_id 前台的消息ID-用于改变前台消息发送状态
     * @return array
     */
    public static function chatRecord($session_id, $message, $message_type = 0, $sender = false, $message_id = false)
    {
        $session = Db::name('kefu_session')->where('id', $session_id)->find();

        if (!$session) {
            return [
                'msgtype' => 'send_message',
                'code'    => 0,
                'data'    => [
                    'msg'        => '发送失败,会话找不到啦！',
                    'message_id' => $message_id
                ]
            ];
        }

        if (!$sender) {
            $sender       = $_SESSION['user_id'];
            $session_user = self::sessionUser($session);
        } else {
            $user = explode('||', $sender);

            if ($user[1] == 'csr' && $user[0] == $session['csr_id']) {
                $session_user = $session['user_id'] . '||user';
            } elseif ($user[1] == 'user' && $user[0] == $session['user_id']) {
                $session_user = $session['csr_id'] . '||csr';
            } else {
                return [
                    'msgtype' => 'send_message',
                    'code'    => 0,
                    'data'    => [
                        'msg'        => '发送失败,无法确定收信人！',
                        'message_id' => $message_id
                    ]
                ];
            }
        }

        // 发信人
        $sender_info = self::userInfo($sender);

        // 收信人信息
        $session_user_info = self::userInfo($session_user);

        $sender_identity = ($sender_info['source'] == 'csr') ? 0 : 1;

        // 还原html
        $message_html = htmlspecialchars_decode($message);

        // 去除样式
        $message_html = preg_replace("/style=.+?['|\"]/i", '', $message_html);
        $message_html = preg_replace("/width=.+?['|\"]/i", '', $message_html);
        $message_html = preg_replace("/height=.+?['|\"]/i", '', $message_html);

        if ($sender_identity == 1) {
            // 过滤除了img的所有标签
            $message_html = strip_tags($message_html, "<img>");
            $message_html = self::removeXss($message_html);
        }

        $message = [
            'session_id'      => $session_id,
            'sender_identity' => $sender_identity,
            'sender_id'       => $sender_info['id'],
            'message'         => htmlspecialchars($message_html),// 入库的消息内容不解码
            'message_type'    => ($message_type == 'auto_reply') ? 0 : $message_type,
            'status'          => 0,
            'createtime'      => time(),
        ];

        // 为小程序用户推送消息
        if (isset($session_user_info['wechat_openid']) && $session_user_info['wechat_openid']) {

            $wxBizMsg = new \addons\kefu\library\WechatCrypto\WXBizMsgCrypt();

            if ($message_type == 1) {
                $result[1][0]      = $message_html;
                $send_message_text = '';
            } else {
                $preg = '/<img.*?src="(.*?)".*?>/is';
                preg_match_all($preg, $message_html, $result, PREG_PATTERN_ORDER); // 匹配img的src
                $send_message_text = strip_tags($message_html);
            }

            // 上传图片素材并发送
            foreach ($result[1] as $key => $value) {

                $url_temp = parse_url($result[1][$key]);
                if (isset($url_temp['path'])) {
                    $media_data = $wxBizMsg->wechatapp->media->uploadImage(ROOT_PATH . 'public' . DS . $url_temp['path']);
                    if (isset($media_data['media_id'])) {
                        $media_obj = new \EasyWeChat\Kernel\Messages\Image($media_data['media_id']);
                        $wxBizMsg->sendMessage($media_obj, $session_user_info['wechat_openid'], $sender);
                    }
                }
            }

            if ($send_message_text) {
                $wxBizMsg->sendMessage($send_message_text, $session_user_info['wechat_openid'], $sender);
            }
        }

        if (Db::name('kefu_record')->insert($message)) {
            $message['record_id'] = Db::name('kefu_record')->getLastInsID(); //消息记录ID

            // 确定会话状态
            Db::name('kefu_session')->where('id', $session['id'])->update([
                'deletetime' => null,
                'createtime' => time()
            ]);

            if (class_exists('\GatewayWorker\Lib\Gateway') && $sender != $session_user && Gateway::isUidOnline($session_user)) {

                // 加上发信人的信息
                $message['id']           = $message['session_id'];
                $message['avatar']       = $sender_info['avatar'];
                $message['nickname']     = $sender_info['nickname'];
                $message['session_user'] = $sender;
                $message['online']       = 1;
                $message['last_message'] = self::formatMessage($message);
                $message['last_time']    = self::formatSessionTime(null);
                $message['message']      = ($message['message_type'] == 1 || $message['message_type'] == 2) ? Common::imgSrcFill($message_html) : $message_html;
                $message['sender']       = 'you';

                // 查询当前用户发送的未读消息条数
                $message['unread_msg_count'] = Db::name('kefu_record')
                    ->where('session_id', $message['session_id'])
                    ->where('sender_identity', $sender_identity)
                    ->where('sender_id', $sender_info['id'])
                    ->where('status', 0)
                    ->count('id');

                Gateway::sendToUid($session_user, json_encode(['msgtype' => 'new_message', 'data' => $message]));

                if ($message_type == 'auto_reply') {
                    // 通知客服端：如果客服端口刚好打开的此用户的窗口->重载消息列表以显示自动回复
                    Gateway::sendToUid($sender, json_encode([
                        'msgtype' => 'reload_record',
                        'data'    => [
                            'session_id' => $message['session_id']
                        ]
                    ]));
                }
            }

            // 用户给客服发送消息，检查知识库自动回复
            $message_text = trim(strip_tags($message_html));// 去除消息中的标签
            $kbs_switch   = Db::name('kefu_config')->where('name', 'kbs_switch')->value('value');
            if ($sender_identity == 1 && $message_text && $kbs_switch) {
                // 读取知识库
                $kbs = Db::name('kefu_kbs')
                    ->where('status', '1')
                    ->where('deletetime', null)
                    ->where("admin_id like :csr_id OR admin_id=''")// id初步like筛选
                    ->bind(['csr_id' => '%' . $session_user_info['id'] . '%'])
                    ->order('weigh desc')
                    ->select();

                // 计算匹配度
                $last_kb_match = 0;
                $best_kb       = [];// 最佳匹配
                $StrComparison = new \addons\kefu\library\StrComparison();
                foreach ($kbs as $key => $kb) {

                    // 去除限定外的知识点
                    if ($kb['admin_id']) {
                        $kb['admin_id'] = explode(',', $kb['admin_id']);
                        if (!in_array($session_user_info['id'], $kb['admin_id'])) {
                            // unset($kbs[$key]);
                            continue;
                        }
                    }

                    $kb_questions = explode(PHP_EOL, $kb['questions']);
                    foreach ($kb_questions as $kb_question) {
                        $kb_question = trim($kb_question);
                        if ($kb_question) {
                            $match_temp = $StrComparison->getSimilar($kb_question, $message_text);
                            if ($match_temp > 0 && $match_temp > $last_kb_match && $match_temp >= $kb['match']) {
                                $last_kb_match = $match_temp;
                                $best_kb       = $kbs[$key];
                            }
                        }
                    }
                }

                // 发送
                if ($best_kb) {
                    self::chatRecord($session_id, $best_kb['answer'], 'auto_reply', $session_user);
                } else {

                    // 读取万能知识
                    $kbs = Db::name('kefu_kbs')
                        ->where('status', '2')
                        ->where('deletetime', null)
                        ->where("admin_id like :csr_id OR admin_id=''")// id初步like筛选
                        ->bind(['csr_id' => '%' . $session_user_info['id'] . '%'])
                        ->order('weigh desc')
                        ->select();
                    foreach ($kbs as $key => $kb) {
                        // 去除限定外的知识点
                        if ($kb['admin_id']) {
                            $kb['admin_id'] = explode(',', $kb['admin_id']);
                            if (!in_array($session_user_info['id'], $kb['admin_id'])) {
                                continue;
                            }
                        }

                        self::chatRecord($session_id, $kbs[$key]['answer'], 'auto_reply', $session_user);
                        break;
                    }
                }
            }

            return [
                'msgtype' => 'send_message',
                'code'    => 1,
                'data'    => [
                    'message_id' => $message_id,
                    'id'         => $message['record_id']
                ]
            ];
        } else {
            return [
                'msgtype' => 'send_message',
                'code'    => 0,
                'data'    => [
                    'message_id' => $message_id,
                    'msg'        => '发送失败,请重试！'
                ]
            ];
        }
    }

    /**
     * 获取一个会话的会话对象
     * @param array session 会话详细信息
     * @param string user_id 带标识符的当前用户id
     * @return string session_user_id 带标识符的会话对象id
     */
    public static function sessionUser($session, $user_id = false)
    {
        if (!$user_id) {
            $user_id = $_SESSION['user_id'];
        }

        $user = explode('||', $user_id);

        if (isset($user[1])) {
            if ($user[1] == 'csr' && $user[0] == $session['csr_id']) {
                return $session['user_id'] . '||user';
            } elseif ($user[1] == 'user' && $user[0] == $session['user_id']) {
                return $session['csr_id'] . '||csr';
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 格式化消息-将图片和连接用文字代替
     * @param array message 消息内容
     * @return string
     */
    public static function formatMessage($message)
    {
        if (!$message) {
            return '';
        }
        if ($message['message_type'] == 0 || $message['message_type'] == 3) {
            $message_text = htmlspecialchars_decode($message['message']);

            // 匹配所有的img标签
            $preg = '/<img.*?src=(.*?)>/is';
            preg_match_all($preg, $message_text, $result, PREG_PATTERN_ORDER);
            $message_text = str_replace($result[0], '[图片]', $message_text);

        } elseif ($message['message_type'] == 1) {
            $message_text = '[图片]';
        } elseif ($message['message_type'] == 2) {
            $message_text = '[链接]';
        } elseif ($message['message_type'] == 4) {
            $message_text = '[商品卡片]';
        } elseif ($message['message_type'] == 5) {
            $message_text = '[订单卡片]';
        } else {
            $message_text = $message['message'];
        }

        return $message_text;
    }

    /**
     * 格式化会话时间-按天顺时针格式化
     * @param int time 时间戳
     * @return string
     */
    public static function formatSessionTime($time = null)
    {
        if (!$time) {
            return date('H:i');
        }
        $now_date  = getdate(time());
        $time_date = getdate($time);

        if (($now_date['year'] === $time_date['year']) && ($now_date['yday'] === $time_date['yday'])) {
            return date('H:i', $time);
        } else {
            return self::formatTime($time);
        }
    }

    /**
     * 格式化时间-按时间差逆时针格式化
     * @param int time 时间戳
     * @return string
     */
    public static function formatTime($time = null)
    {
        $now_time = time();
        $time     = ($time === null || $time > $now_time || $time == $now_time) ? $now_time - 1 : intval($time);
        $lang     = [
            '%d second%s ago' => '%d秒前',
            '%d minute%s ago' => '%d分钟前',
            '%d hour%s ago'   => '%d小时前',
            '%d day%s ago'    => '%d天前',
            '%d week%s ago'   => '%d周前',
            '%d month%s ago'  => '%d月前',
            '%d year%s ago'   => '%d年前',
        ];
        \think\Lang::set($lang);
        $date = \fast\Date::human($time);
        return $date;
    }

    /**
     * 发送消息
     * @param string client_id 链接ID
     * @param string msg 消息内容
     */
    public static function showMsg($client_id, $msg = '')
    {
        Gateway::sendToClient($client_id, json_encode([
            'code'    => 0,
            'msgtype' => 'show_msg',
            'msg'     => $msg,
        ]));
    }

    /**
     * 获取或改变客服代表状态
     * @param int $status 状态标识符
     * @param string $csr 带标识符的客服代表ID,或者客服数据数组
     * @return string 状态
     */
    public static function csrStatus($status = null, $csr = false)
    {
        if (!$csr) {
            $csr = $_SESSION['user_id'];
        }

        if (!is_array($csr)) {
            $csr = self::userInfo($csr);
        }

        if ($csr['source'] == 'csr') {

            if ($status !== null) {
                Db::name('kefu_csr_config')->where('admin_id', $csr['id'])->update(['status' => $status]);
            } else {
                $status = $csr['status'];
            }

            return $status;
        } else {
            return 'none';
        }
    }

    /**
     * 获取用户的未读消息->获取他的会话->获取会话中的非他自己发送的未读消息
     * @param string user_id 带标识符的用户id
     * @param bool is_latest 是否只获取用户已进入网站但未链接websocket期间的消息
     * @return string
     */
    public static function getUnreadMessages($user_id, $is_latest = false)
    {
        $new_msg = '';

        $user = self::userInfo($user_id);

        if ($user['source'] == 'csr') {

            // 客服只读取最近沟通40条会话记录中的未读消息，防止会话非常多的情况下，占用cpu过高
            $session_list = Db::name('kefu_session')
                ->where('csr_id', $user['id'])
                ->where('deletetime', null)
                ->order('createtime desc')
                ->limit(40)
                ->select();
        } else {
            $session_list = Db::name('kefu_session')->where('user_id', $user['id'])->order('createtime desc')->select();
        }

        foreach ($session_list as $key => $value) {

            $session_user = self::sessionUser($value, $user_id);

            $where['session_id']      = $value['id'];
            $where['sender_identity'] = ($user['source'] == 'csr') ? 1 : 0;
            $where['status']          = 0;

            if ($is_latest) {
                $where['createtime'] = ['>', time() - 10];
            }

            $new_msg = Db::name('kefu_record')->where($where)->order('createtime desc')->find();

            if ($new_msg) {
                $session_user_info = self::userInfo($session_user);
                $new_msg           = $session_user_info['nickname'] . ':' . self::formatMessage($new_msg);
                break;
            }
        }

        return $new_msg;
    }

    /*
     * 轨迹分析
     */
    public static function trajectoryAnalysis($url)
    {
        if (!$url) {
            return '';
        }

        $parse_url = parse_url($url);
        if (!$parse_url) {
            return $url;
        }

        $parse_url['query'] = isset($parse_url['query']) ? self::convertUrlQuery($parse_url['query']) : false;
        $data['host_name']  = false;
        $data['search_key'] = false;

        if (isset($parse_url['host'])) {

            if ($parse_url['host'] == 'www.baidu.com') {
                $data['host_name']  = '百度';
                $data['search_key'] = isset($parse_url['query']['wd']) ? $parse_url['query']['wd'] : '';
            }

            if ($parse_url['host'] == 'www.so.com') {
                $data['host_name']  = '360搜索';
                $data['search_key'] = isset($parse_url['query']['q']) ? $parse_url['query']['q'] : '';
            }
        }

        if ($data['host_name']) {
            $res = $data['host_name'];
        } else {
            return $url;
        }

        if ($data['search_key']) {
            $res .= '搜索 ' . $data['search_key'];
        }

        return $res;
    }

    /**
     * 解析一个url的参数
     *
     * @param string    query
     * @return    array    params
     */
    public static function convertUrlQuery($query)
    {
        $queryParts = explode('&', $query);

        $params = [];
        foreach ($queryParts as $param) {
            $item             = explode('=', $param);
            $params[$item[0]] = $item[1];
        }

        return $params;
    }

    public static function removeXss($val)
    {
        if (function_exists('xss_clean')) {
            return xss_clean($val);
        }
        $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);

        $search = 'abcdefghijklmnopqrstuvwxyz';
        $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $search .= '1234567890!@#$%^&*()';
        $search .= '~`";:?+/={}[]-_|\'\\';
        for ($i = 0; $i < strlen($search); $i++) {
            $val = preg_replace('/(&#[xX]0{0,8}' . dechex(ord($search[$i])) . ';?)/i', $search[$i], $val); // with a ;
            $val = preg_replace('/(&#0{0,8}' . ord($search[$i]) . ';?)/', $search[$i], $val); // with a ;
        }

        $ra1   = [
            'javascript',
            'vbscript',
            'expression',
            'applet',
            'meta',
            'xml',
            'blink',
            'link',
            'style',
            'script',
            'embed',
            'object',
            'iframe',
            'frame',
            'frameset',
            'ilayer',
            'layer',
            'bgsound',
            'title',
            'base'
        ];
        $ra2   = [
            'onabort',
            'onactivate',
            'onafterprint',
            'onafterupdate',
            'onbeforeactivate',
            'onbeforecopy',
            'onbeforecut',
            'onbeforedeactivate',
            'onbeforeeditfocus',
            'onbeforepaste',
            'onbeforeprint',
            'onbeforeunload',
            'onbeforeupdate',
            'onblur',
            'onbounce',
            'oncellchange',
            'onchange',
            'onclick',
            'oncontextmenu',
            'oncontrolselect',
            'oncopy',
            'oncut',
            'ondataavailable',
            'ondatasetchanged',
            'ondatasetcomplete',
            'ondblclick',
            'ondeactivate',
            'ondrag',
            'ondragend',
            'ondragenter',
            'ondragleave',
            'ondragover',
            'ondragstart',
            'ondrop',
            'onerror',
            'onerrorupdate',
            'onfilterchange',
            'onfinish',
            'onfocus',
            'onfocusin',
            'onfocusout',
            'onhelp',
            'onkeydown',
            'onkeypress',
            'onkeyup',
            'onlayoutcomplete',
            'onload',
            'onlosecapture',
            'onmousedown',
            'onmouseenter',
            'onmouseleave',
            'onmousemove',
            'onmouseout',
            'onmouseover',
            'onmouseup',
            'onmousewheel',
            'onmove',
            'onmoveend',
            'onmovestart',
            'onpaste',
            'onpropertychange',
            'onreadystatechange',
            'onreset',
            'onresize',
            'onresizeend',
            'onresizestart',
            'onrowenter',
            'onrowexit',
            'onrowsdelete',
            'onrowsinserted',
            'onscroll',
            'onselect',
            'onselectionchange',
            'onselectstart',
            'onstart',
            'onstop',
            'onsubmit',
            'onunload'
        ];
        $ra    = array_merge($ra1, $ra2);
        $found = true;
        while ($found == true) {

            $val_before = $val;
            for ($i = 0; $i < sizeof($ra); $i++) {
                $pattern = '/';
                for ($j = 0; $j < strlen($ra[$i]); $j++) {
                    if ($j > 0) {
                        $pattern .= '(';
                        $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                        $pattern .= '|';
                        $pattern .= '|(&#0{0,8}([9|10|13]);)';
                        $pattern .= ')*';
                    }
                    $pattern .= $ra[$i][$j];
                }
                $pattern     .= '/i';
                $replacement = substr($ra[$i], 0, 2) . '<k>' . substr($ra[$i], 2);
                $val         = preg_replace($pattern, $replacement, $val);
                if ($val_before == $val) {
                    $found = false;
                }
            }
        }
        return $val;
    }

    /*
     * 检查/过滤变量
     */
    public static function checkVariable(&$variable)
    {
        $variable = htmlspecialchars($variable);
        $variable = stripslashes($variable); // 删除反斜杠
        $variable = addslashes($variable); // 转义特殊符号
        $variable = trim($variable); // 去除字符两边的空格
    }
}
