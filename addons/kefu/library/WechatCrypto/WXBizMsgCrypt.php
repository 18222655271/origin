<?php

/**
 * 对公众平台发送给公众账号的消息加解密代码.
 * 白衣素袖：增加消息处理辅助方法
 * @copyright Copyright (c) 1998-2014 Tencent Inc.
 */

namespace addons\kefu\library\WechatCrypto;

use think\Db;
use EasyWeChat\Factory;
use GatewayWorker\Lib\Gateway;
use addons\kefu\library\Common;
use addons\kefu\library\WechatCrypto\SHA1;
use addons\kefu\library\WechatCrypto\ErrorCode;
use addons\kefu\library\WechatCrypto\XMLParse;
use addons\kefu\library\WechatCrypto\Prpcrypt;

/**
 * 1.第三方回复加密消息给公众平台；
 * 2.第三方收到公众平台发送的消息，验证消息的安全性，并对消息进行解密。
 */
class WXBizMsgCrypt
{
    // 微信配置
    private $wechat = [];

    // EasyWeChat APP
    public $wechatapp = null;

    /**
     * 构造函数
     */
    public function __construct()
    {

        $wechat_temp = Db::name('kefu_config')
            ->whereIn('name', 'wechat_app_id,wechat_app_secret,wechat_token,wechat_encodingkey')
            ->select();

        foreach ($wechat_temp as $key => $value) {
            $this->wechat[$value['name']] = $value['value'];
        }

        // 初始化EasyWeChat
        $config          = [
            'app_id'  => $this->wechat['wechat_app_id'],
            'secret'  => $this->wechat['wechat_app_secret'],
            'token'   => $this->wechat['wechat_token'],
            'aes_key' => $this->wechat['wechat_encodingkey'],
            /*'log' => [
                'level' => 'debug',
                'file' => RUNTIME_PATH . 'log/kefu_wechat.log',
            ],*/
        ];
        $this->wechatapp = Factory::miniProgram($config);
    }

    /**
     * 将公众平台回复用户的消息加密打包.
     * <ol>
     *    <li>对要发送的消息进行AES-CBC加密</li>
     *    <li>生成安全签名</li>
     *    <li>将消息密文和安全签名打包成xml格式</li>
     * </ol>
     *
     * @param $replyMsg string 公众平台待回复用户的消息，xml格式的字符串
     * @param $timeStamp string 时间戳，可以自己生成，也可以用URL参数的timestamp
     * @param $nonce string 随机串，可以自己生成，也可以用URL参数的nonce
     * @param &$encryptMsg string 加密后的可以直接回复用户的密文，包括msg_signature, timestamp, nonce, encrypt的xml格式的字符串,
     *                      当return返回0时有效
     *
     * @return int 成功0，失败返回对应的错误码
     */
    public function encryptMsg($replyMsg, $timeStamp, $nonce, &$encryptMsg)
    {
        $pc = new Prpcrypt($this->wechat['wechat_encodingkey']);

        //加密
        $array = $pc->encrypt($replyMsg, $this->wechat['wechat_app_id']);
        $ret   = $array[0];
        if ($ret != 0) {
            return $ret;
        }

        if ($timeStamp == null) {
            $timeStamp = time();
        }
        $encrypt = $array[1];

        //生成安全签名
        $sha1  = new SHA1;
        $array = $sha1->getSHA1($this->wechat['wechat_token'], $timeStamp, $nonce, $encrypt);
        $ret   = $array[0];
        if ($ret != 0) {
            return $ret;
        }
        $signature = $array[1];

        //生成发送的xml
        $xmlparse   = new XMLParse;
        $encryptMsg = $xmlparse->generate($encrypt, $signature, $timeStamp, $nonce);
        return ErrorCode::$OK;
    }

    /**
     * 检验消息的真实性，并且获取解密后的明文.
     * <ol>
     *    <li>利用收到的密文生成安全签名，进行签名验证</li>
     *    <li>若验证通过，则提取xml中的加密消息</li>
     *    <li>对消息进行解密</li>
     * </ol>
     *
     * @param $msgSignature string 签名串，对应URL参数的msg_signature
     * @param $timestamp string 时间戳 对应URL参数的timestamp
     * @param $nonce string 随机串，对应URL参数的nonce
     * @param $postData string 密文，对应POST请求的数据
     * @param &$msg string 解密后的原文，当return返回0时有效
     *
     * @return int 成功0，失败返回对应的错误码
     */
    public function decryptMsg($msgSignature, $timestamp = null, $nonce, $encrypt, &$msg)
    {
        if (strlen($this->wechat['wechat_encodingkey']) != 43) {
            return ErrorCode::$IllegalAesKey;
        }

        $pc = new Prpcrypt($this->wechat['wechat_encodingkey']);

        if ($timestamp == null) {
            $timestamp = time();
        }

        //验证安全签名
        $sha1  = new SHA1;
        $array = $sha1->getSHA1($this->wechat['wechat_token'], $timestamp, $nonce, $encrypt);
        $ret   = $array[0];

        if ($ret != 0) {
            return $ret;
        }

        $signature = $array[1];
        if ($signature != $msgSignature) {
            return ErrorCode::$ValidateSignatureError;
        }

        $result = $pc->decrypt($encrypt, $this->wechat['wechat_app_id']);
        if ($result[0] != 0) {
            return $result[0];
        }
        $msg = $result[1];

        return ErrorCode::$OK;
    }

    /**
     * 保存用户发送的图片至服务器
     * @param  [type] $media_id 临时素材ID
     * @param string $save_dir 保存目录
     * @return string           文件完整路径
     */
    public function saveImg($media_id, $save_dir = './uploads/')
    {
        $stream = $this->wechatapp->media->get($media_id);
        if ($stream instanceof \EasyWeChat\Kernel\Http\StreamResponse) {

            $save_dir = $save_dir . date('Ymd') . '/';

            if (!is_dir($save_dir)) {
                if (!mkdir($save_dir, 0777, true)) {
                    return false;
                }
            }

            $filename = $stream->save($save_dir);

            return $save_dir . $filename;
        } else {
            return false;
        }
    }

    /**
     * 发送消息至小程序用户
     * @param  [type] $message 文本或消息对象
     * @param  [type] $openid  [description]
     * @param  [type] $sender  带标识的发送人
     * @return [type]          [description]
     */
    public function sendMessage($message, $openid, $sender = false)
    {
        $res = $this->wechatapp->customer_service->message($message)->to($openid)->send();

        switch ($res['errcode']) {
            case '-1':
                $msg = '发送失败，系统繁忙，请重试！';
                break;
            case '40001':
                $msg = '发送失败，AppSecret错误！';
                break;
            case '40002':
                $msg = '发送失败，凭证不合法！';
                break;
            case '40003':
                $msg = '发送失败，openid不合法！';
                break;
            case '45015':
                $msg = '发送失败，回复时间超限制！';
                break;
            case '45047':
                $msg = '发送条数超限，用户将不会收到此消息！';
                break;
            case '48001':
                $msg = '请确保小程序已获取发送客服消息功能的API权限！';
                break;

            default:
                $msg = '';
                break;
        }

        // 只在workerman环境(客服发送消息时)，进行提示
        if (class_exists('\GatewayWorker\Lib\Gateway') && $msg && $sender) {
            Gateway::sendToUid($sender, json_encode([
                'code'    => 0,
                'msgtype' => 'show_msg',
                'msg'     => $msg,
            ]));
        }

        return true;
    }

    /**
     * 建立用户->分配客服
     * @param  [type] $open_id [description]
     * @return [type]          [description]
     */
    public function userInitialize($open_id)
    {
        $kefu_user = Db::name('kefu_user')->where('wechat_openid', $open_id)->find();

        if (!$kefu_user) {

            // 建立用户
            $tourists_max_id = Db::name('kefu_user')->max('id');

            $kefu_user = [
                'avatar'        => '', // 随机头像->算了算了
                'nickname'      => '小程序用户 ' . $tourists_max_id,
                'wechat_openid' => $open_id,
                'createtime'    => time(),
            ];

            if (Db::name('kefu_user')->insert($kefu_user)) {
                $kefu_user['id'] = Db::name('kefu_user')->getLastInsID();
            }
        }

        // 查询之前的客服代表
        $session = Db::name('kefu_session')
            ->alias('s')
            ->field('s.*,a.id as admin_id,a.nickname')
            ->join('admin a', 's.csr_id=a.id')
            ->where('s.user_id', $kefu_user['id'])
            ->where('s.deletetime', null)
            ->find();

        // 有客服代表，但客服代表不在线，重新分配
        $is_csr_distribution = false;
        if ($session) {
            $csr_status = Db::name('kefu_csr_config')->where('admin_id', $session['admin_id'])->value('status');

            if ($csr_status != 3) {
                $is_csr_distribution = true;
            }
        }

        if (!$session || $is_csr_distribution) {

            // 客服分配
            $csr = Common::getAppropriateCsr();
            if ($csr) {
                $session = Common::distributionCsr($csr, $kefu_user['id'] . '||user');
            } else {
                $data = [
                    'session'   => false,
                    'kefu_user' => $kefu_user,
                    'code'      => 1,
                    'msg'       => '非常抱歉，当前无在线客服，您可以直接在此留言，谢谢您的支持！',
                ];
                return $data;
            }
        }

        if ($session) {

            // 记录客服接待人数
            Db::name('kefu_csr_config')->where('admin_id', $session['admin_id'])->inc('reception_count')->update([
                'last_reception_time' => time(),
            ]);

            $data = [
                'session'   => $session,
                'kefu_user' => $kefu_user,
                'code'      => 2,
            ];
            return $data;
        } else {

            $data = [
                'session' => false,
                'code'    => 0,
                'msg'     => '分配客服代表失败！',
            ];
            return $data;
        }

    }

}
