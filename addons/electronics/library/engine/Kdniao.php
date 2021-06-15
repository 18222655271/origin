<?php

namespace addons\electronics\library\engine;

use fast\Http;
use addons\electronics\model\Area;

/**
 * 快递鸟电子面单
 * @author amplam 122795200@qq.com
 * @Date   2020年2月22日
 */
class Kdniao extends Server
{

    /* @var array $config 配置 */
    private $config;

    //电子面单请求url，
    //正式环境地址
    private $reqURL = 'http://api.kdniao.com/api/Eorderservice';
    // 沙箱测试环境地址
    private $sandboxURL = 'http://sandboxapi.kdniao.com:8080/kdniaosandbox/gateway/exterfaceInvoke.json';
    /**
     * 物流信息查询地址
     * @var string
     */
    private $queryUrl = "http://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx";


    /**
     * 构造方法
     * WxPay constructor.
     * @param $config
     */
    public function __construct($config = array())
    {
        $this->config = $config;
        if ($config['is_debug']==1){
            $this->reqURL=$this->sandboxURL;
        }
    }

    /**
     * 电子面单构建
     * @param $express_info array 物流快递的信息
     * @param $data array order_code:商城的订单号 name:收货人姓名 mobile：收货人手机 province：省份
     * city：城市 area：区域 address：详细地址  goods_name：产品信息
     * @return mixed|string
     */
    public function build($express_info, $data)
    {
        $eorder = [];
        $eorder["ShipperCode"] = $express_info['express_code1'];
        $eorder["OrderCode"] = $data['order_code'];//商城的订单号
        $eorder["PayType"] = $express_info['pay_type'];//邮费支付方式:1-现付，2-到付，3-月结，4-第三方支付(仅SF支持)
        $eorder["ExpType"] = 1;
        $eorder["IsReturnPrintTemplate"] = 1;
        $eorder["CustomerName"] = $express_info["customer_name"];//电子面单帐号
        $eorder["CustomerPwd"] = $express_info["customer_pwd"];//电子面单密码
        $eorder["MonthCode"] = $express_info["month_code"];//月结号
        $eorder["SendSite"] = $express_info["send_site"];//网点编码
        $eorder["SendStaff"] = $express_info["send_staff"];//网点名称

        $sender = [];
        //发货人
        $sender["Name"] = $express_info['name'];
        $sender["Mobile"] = $express_info['phone'];
        $sender["ProvinceName"] = Area::getNameById($express_info['province_id']);
        $sender["CityName"] = Area::getNameById($express_info["city_id"]);
        $sender["ExpAreaName"] = Area::getNameById($express_info['region_id']);
        $sender["Address"] = $express_info["detail"];

        //收货人
        $receiver = [];
        $receiver["Name"] = $data['name'];
        $receiver["Mobile"] = $data['mobile'];
        $receiver["ProvinceName"] = is_int($data['province']) ? Area::getNameById($data['province']) : $data['province'];
        $receiver["CityName"] = is_int($data['city']) ? Area::getNameById($data['city']) : $data['city'];
        $receiver["ExpAreaName"] = is_int($data['area']) ? Area::getNameById($data['area']) : $data['area'];;
        $receiver["Address"] = $data['address'];
        //物品信息
        $commodityOne = [];
        $commodityOne["GoodsName"] = $data['goods_name'];//商品信息
        $commodity = [];
        $commodity[] = $commodityOne;

        $eorder["Sender"] = $sender;
        $eorder["Receiver"] = $receiver;
        $eorder["Commodity"] = $commodity;
        //构造电子面单提交信息

        //调用电子面单
        $jsonParam = json_encode($eorder, JSON_UNESCAPED_UNICODE);
        $result = $this->submitEOrder($jsonParam);
        $result = json_decode($result, true);

        if (!$result['Success']) {
            $this->error = $result['Reason'];
            return false;
        }

        return $result;
    }

    /**
     * 物流信息查询
     * @param $express_code 物流单号
     * @param string $shipper_code 物流代码
     * @return bool|false|mixed|string
     */
    public function query($express_code, $shipper_code = "")
    {

        // 缓存索引
        $cacheIndex = 'electronics_kdniao_' . $express_code.$shipper_code;
        if ($data = cache($cacheIndex)) {
            return $data;
        }

        if (!$shipper_code) {
            $data = $this->getOrderTracesByJson($express_code);
            $data = json_decode($data);
            if (!isset($data->Shippers) || !isset($data->Shippers[0]->ShipperCode)) {
                $this->error = "物流代码错误";
                return false;
            }
            $shipper_code = $data->Shippers[0]->ShipperCode;
        }

        $requestData = "{'OrderCode':'','ShipperCode':'{$shipper_code}','LogisticCode':'{$express_code}'}";

        $datas = array(
            'EBusinessID' => $this->config['ebusiness_id'],
            'RequestType' => '1002',
            'RequestData' => urlencode($requestData),
            'DataType' => '2',
        );
        $datas['DataSign'] = $this->encrypt($requestData, $this->config['app_key']);
        $result = Http::post($this->queryUrl, $datas);
        $result = json_decode($result, true);

        if (!$result['Success']) {
            $this->error = $result['Reason'];
            return false;
        }
        //格式转化和快递100一样
        $retrun_data = [
            'message' => 'ok',
            'nu' => $express_code,
            'ischeck' => $result['State'] == 3 ? 1 : 0,
            'com' => $shipper_code,
            'state' => $result['State'],
        ];

        foreach ($result['Traces'] as $r) {
            $temp['time'] = $r['AcceptTime'];
            $temp['ftime'] = $r['AcceptTime'];
            $temp['context'] = $r['AcceptStation'];
            $retrun_data['data'][] = $temp;
        }

        //缓存5分钟
        cache($cacheIndex, json_encode($retrun_data), 300);
        return json_encode($retrun_data);
    }

    /**
     * Json方式 调用电子面单接口
     */
    protected function submitEOrder($requestData)
    {
        $datas = array(
            'EBusinessID' => $this->config['ebusiness_id'],
            'RequestType' => '1007',
            'RequestData' => urlencode($requestData),
            'DataType' => '2',
        );
        $datas['DataSign'] = $this->encrypt($requestData, $this->config['app_key']);
        $result = Http::post($this->reqURL, $datas);
        return $result;
    }

    /**
     * 电商Sign签名生成
     * @param data 内容
     * @param appkey Appkey
     * @return DataSign签名
     */
    private function encrypt($data, $appkey)
    {
        return urlencode(base64_encode(md5($data . $appkey)));
    }
    /**
     * Json方式 单号识别
     */
    public function getOrderTracesByJson($code)
    {
        $requestData = "{'LogisticCode':'{$code}'}";
        $datas = array(
            'EBusinessID' => $this->config['ebusiness_id'],
            'RequestType' => '2002',
            'RequestData' => urlencode($requestData),
            'DataType'    => '2',
        );
        $datas['DataSign'] = $this->encrypt($requestData, $this->config['app_key']);

        $result = Http::post($this->queryUrl, $datas);
        return $result;
    }
}