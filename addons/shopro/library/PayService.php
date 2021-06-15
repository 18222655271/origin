<?php

namespace addons\shopro\library;

use Yansongda\Pay\Pay;
use Yansongda\Pay\Log;
use addons\shopro\exception\Exception;

class PayService
{
    protected $config;
    protected $platform;
    protected $payment;
    protected $notify_url;
    public $method;


    public function __construct($payment, $platform = '', $notify_url = '', $type = 'pay')
    {
        $this->platform = $platform;
        $this->payment = $payment;
        $this->notify_url = $notify_url;
        $this->type = $type;

        $this->setPaymentConfig();
    }

    private function setPaymentConfig()
    {
        $paymentConfig = json_decode(\addons\shopro\model\Config::get(['name' => $this->payment])->value, true);

        // 如果是支付，并且不是 复制地址的支付宝支付
        if ($this->type == 'pay' && $this->platform != 'url' && !in_array($this->platform, $paymentConfig['platform'])) {
            new Exception('暂不支持该方式付款');
        }

        $this->config = $paymentConfig;
        $this->config['notify_url'] = $this->notify_url;

        if ($this->payment === 'wechat') {
            // 根据不同平台设置相应的 appid
            $this->setWechatAppId();
        }

        // 设置支付证书路径
        $this->setCert();
    }

    private function setWechatAppId()
    {
        switch ($this->platform) {
            case 'wxOfficialAccount':
                $platformConfig = json_decode(\addons\shopro\model\Config::get(['name' => $this->platform])->value, true);
                if(isset($this->config['mode']) && $this->config['mode'] === 'service') {
                    $this->config['sub_app_id'] = $platformConfig['app_id'];
                }else {
                    $this->config['app_id'] = $platformConfig['app_id'];
                }
                break;
            case 'wxMiniProgram':
                $platformConfig = json_decode(\addons\shopro\model\Config::get(['name' => $this->platform])->value, true);
                if(isset($this->config['mode']) && $this->config['mode'] === 'service') {
                    $this->config['sub_miniapp_id'] = $platformConfig['app_id'];
                    $this->config['sub_app_id'] = $platformConfig['app_id'];
                }else {
                    $this->config['miniapp_id'] = $platformConfig['app_id'];
                    $this->config['app_id'] = $platformConfig['app_id'];        // 小程序微信企业付款
                }
                break;
            case 'H5':
                $platformConfig = json_decode(\addons\shopro\model\Config::get(['name' => $this->platform])->value, true);
                if(isset($this->config['mode']) && $this->config['mode'] === 'service') {
                    $this->config['sub_app_id'] = $platformConfig['app_id'];
                }else {
                    $this->config['app_id'] = $platformConfig['app_id'];
                }
                break;
            case 'App':
                $platformConfig = json_decode(\addons\shopro\model\Config::get(['name' => 'App'])->value, true);
                if(isset($this->config['mode']) && $this->config['mode'] === 'service') {
                    $this->config['sub_appid'] = $platformConfig['app_id'];
                    $this->config['sub_app_id'] = $platformConfig['app_id'];
                }else {
                    $this->config['appid'] = $platformConfig['app_id'];         // 微信 App 支付使用这个
                    $this->config['app_id'] = $platformConfig['app_id'];        // 微信 App 支付退款使用的是这个
                }

                break;
        }
    }


    // 处理证书路径
    private function setCert()
    {
        // 处理证书路径
        if ($this->payment == 'wechat') {
            // 微信支付证书
            $this->config['cert_client'] = ROOT_PATH . 'public' . $this->config['cert_client'];
            $this->config['cert_key'] = ROOT_PATH . 'public' . $this->config['cert_key'];
        } else {
            // 支付宝证书路径
            $end = substr($this->config['ali_public_key'], -4);
            if ($end == '.crt') {
                $this->config['ali_public_key'] = ROOT_PATH . 'public' . $this->config['ali_public_key'];
            }
            $this->config['app_cert_public_key'] = ROOT_PATH . 'public' . $this->config['app_cert_public_key'];
            $this->config['alipay_root_cert'] = ROOT_PATH . 'public' . $this->config['alipay_root_cert'];
        }
    }


    private function setPaymentMethod()
    {
        $method = [
            'wechat' => [
                'wxOfficialAccount' => 'mp',   //公众号支付 Collection
                'wxMiniProgram' => 'miniapp', //小程序支付
                'H5' => 'wap', //手机网站支付 Response
                'App' => 'app' //APP 支付 JsonResponse
            ],
            'alipay' => [
                'wxOfficialAccount' => 'wap',   //手机网站支付 Response
                'wxMiniProgram' => 'wap', //小程序支付
                'H5' => 'wap', //手机网站支付 Response
                'url' => 'wap', //手机网站支付 Response
                'App' => 'app' //APP 支付 JsonResponse
            ],
        ];

        $this->method = $method[$this->payment][$this->platform];
    }

    public function create($order)
    {
        //        $order = [
        //            'out_trade_no' => time(),
        //            'total_fee' => '1', // **单位：分**
        //            'body' => 'test body - 测试',
        //            'openid' => 'onkVf1FjWS5SBIixxxxxxx', //微信需要带openid过来
        //        ];

        // 设置支付方式
        $this->setPaymentMethod();

        $method = $this->method;
        switch ($this->payment) {
            case 'wechat':
                $order['total_fee'] = $order['total_fee'] * 100;
                $pay = Pay::wechat($this->config)->$method($order);

                break;
            case 'alipay':
                if (in_array($this->platform, ['wxOfficialAccount', 'wxMiniProgram', 'H5'])) {
                    // 返回支付宝支付链接
                    $pay = request()->domain() . '/addons/shopro/pay/alipay?order_sn=' . $order['out_trade_no'];
                } else {
                    if ($this->method == 'wap') {
                        // 支付宝 wap 支付，增加 return_url
                        // 获取 h5 域名
                        $platformConfig = json_decode(\addons\shopro\model\Config::get(['name' => 'shopro'])->value, true);
                        // 如果域名存在，增加 return_url
                        if ($platformConfig && isset($platformConfig['domain'])) {
                            $start = substr($platformConfig['domain'], -1) == '/' ? "" : "/";
                            $this->config['return_url'] = $platformConfig['domain'] . $start . "pages/order/payment/result?orderSn=" . $order['out_trade_no'] . "&type=alipay&pay=1";
                        }
                    }

                    $pay = Pay::alipay($this->config)->$method($order);
                }

                break;
        }

        return $pay;
    }

    // 企业付款
    public function transfer($payload)
    {
        $code = 0;
        $response = [];
        // 支付宝返回结果
        // [code] => 10000
        // [msg] => Success
        // [order_id] => 20210309110070000006730051669626
        // [out_biz_no] => W202107231625700647008000
        // [pay_fund_order_id] => 20210309110070001506730065959451
        // [status] => SUCCESS
        // [trans_date] => 2021-03-09 10:42:23

        // 微信返回结果
        // [return_code] => SUCCESS
        // [return_msg] => Array
        //     (
        //     )
        // [mch_appid] => wx38939920ace0d244
        // [mchid] => 1481069012
        // [nonce_str] => O9fwzb6HDGO2zU6H
        // [result_code] => SUCCESS
        // [partner_trade_no] => W202106361960501154008000
        // [payment_no] => 10100294905592102257932685920233
        // [payment_time] => 2021-02-25 18:36:30
        switch ($this->payment) {
            case 'wechat':
                $payload['amount'] = $payload['amount'] * 100;
                $response = Pay::wechat($this->config)->transfer($payload);
                if ($response['return_code'] === 'SUCCESS' && $response['result_code'] === 'SUCCESS') {
                    $code = 1;
                }
                break;
            case 'alipay':
                $response = Pay::alipay($this->config)->transfer($payload);
                if ($response['code'] === '10000' && $response['status'] === 'SUCCESS') {
                    $code = 1;
                }
                break;
        }

        return [$code, $response];
    }


    public function notify($callback)
    {
        $pay = $this->getPay();

        try {
            $data = $pay->verify(); // 是的，验签就这么简单！

            $result = $callback($data, $pay);

            // Log::debug('Wechat notify', $data->all());
        } catch (\Exception $e) {
            // $e->getMessage();
        }

        return $result;
    }


    public function refund($order_data)
    {
        $pay = $this->getPay();

        $order_data['type'] = $this->platform == 'wxMiniProgram' ? 'miniapp' : '';

        $result = $pay->refund($order_data);

        return $result;
    }


    public function notifyRefund($callback)
    {
        $pay = $this->getPay();

        try {
            $data = $pay->verify(null, true); // 是的，验签就这么简单！

            $result = $callback($data, $pay);

            // Log::debug('Wechat notify', $data->all());
        } catch (\Exception $e) {
            // $e->getMessage();
        }

        return $result;
    }


    private function getPay()
    {
        switch ($this->payment) {
            case 'wechat':
                $pay = Pay::wechat($this->config);
                break;
            case 'alipay':
                $pay = Pay::alipay($this->config);
                break;
            default:
                new Exception('支付方式不支持');
        }

        return $pay;
    }
}
