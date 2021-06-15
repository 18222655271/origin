<?php

namespace addons\electronics\library;

class Application
{

    /**
     * 配置信息
     * @var array
     */
    private $config = [];

    /**
     * 服务提供者
     * @var array
     */
    private $providers = [
        'kdniao'    => 'Kdniao',
        'kuaidi100' => 'Kuaidi100',
    ];


    private $engine;    // 当前引擎类

    public function __construct($options = [])
    {
        $this->config = array_merge($this->config, is_array($options) ? $options : []);

        //注册服务提供者
        $this->engine = $this->registerProviders();
    }
    /**
     * 注册服务提供者
     */
    private function registerProviders()
    {
        $objname = __NAMESPACE__ . "\\engine\\" . $this->providers[$this->config['key']];
        return new $objname($this->config);
    }

    /**
     * 电子面单构建
     * @param $express_info array 物流快递的信息
     * @param $data array order_code:商城的订单号 name:收货人姓名 mobile：收货人手机 province：省份
     * city：城市 area：区域 address：详细地址  goods_name：产品信息
     * @return array
     * Order['LogisticCode']:快递单号，
     * PrintTemplate：面单打印模板内容
     * 其他返回参数可以查看http://www.kdniao.com/file/%E5%BF%AB%E9%80%92%E9%B8%9F%E6%8E%A5%E5%8F%A3%E6%8A%80%E6%9C%AF%E6%96%87%E6%A1%A3v5.21.pdf
     */
    public function build($express_info,$data)
    {
        return $this->engine->build($express_info,$data);
    }

    /**
     * @param $express_code 物流单号
     * @param string $shipper_code 物流代码
     * @return bool|false|mixed|string
     */
    public function query($express_code, $shipper_code = "")
    {
        return $this->engine->query($express_code,$shipper_code);
    }
    /**
     * 获取错误信息
     * @return mixed
     */
    public function getError()
    {
        return $this->engine->getError();
    }
}
