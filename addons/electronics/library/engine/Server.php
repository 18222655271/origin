<?php

namespace addons\electronics\library\engine;


/**
 * 抽象类
 * Class server
 * @package addons\electronics\library\engine
 */
abstract class Server
{
    /**
     * 错误信息
     * @var
     */
    protected $error;

    /**
     * 返回错误信息
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 电子面单构建
     * @param $express_info array 物流快递的信息
     * @param $data array order_code:商城的订单号 name:收货人姓名 mobile：收货人手机 province：省份
     * city：城市 area：区域 address：详细地址  goods_name：产品信息
     * @return mixed
     */
    abstract function build($express_info,$receiver);
    /**
     * 物流信息查询
     * @param $express_code 物流单号
     * @param string $shipper_code 物流代码
     * @return bool|false|mixed|string
     */
    abstract function query($express_code, $shipper_code);

}
