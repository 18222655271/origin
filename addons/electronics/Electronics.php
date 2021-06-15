<?php

namespace addons\electronics;

use app\common\library\Menu;
use app\common\model\Area;
use think\Addons;

/**
 * 插件
 */
class Electronics extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu = [];
        $config_file = ADDON_PATH . "electronics" . DS . 'config' . DS . "menu.php";
        if (is_file($config_file)) {
            $menu = include $config_file;
        }
        if ($menu) {
            Menu::create($menu);
        }
        return true;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall()
    {
        $info = get_addon_info('electronics');
        Menu::delete(isset($info['first_menu']) ? $info['first_menu'] : 'electronics');
        return true;
    }

    /**
     * 插件启用方法
     */
    public function enable()
    {
        $info = get_addon_info('electronics');
        Menu::enable(isset($info['first_menu']) ? $info['first_menu'] : 'electronics');
    }

    /**
     * 插件禁用方法
     */
    public function disable()
    {
        $info = get_addon_info('electronics');
        Menu::disable(isset($info['first_menu']) ? $info['first_menu'] : 'electronics');
    }

    /**
     * 电子面单构建
     * @param $data array
     * express_id：物流快递信息id
     * order_code:商城的订单号
     * name:收货人姓名
     * mobile：收货人手机
     * province：省份
     * city：城市
     * area：区域
     * address：详细地址
     * goods_name：产品信息
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function electronicsBuild($data)
    {
        $config = array();
        foreach ($this->getConfig() as $key => $r) {
            if ($r['is_open'] == 1) {
                $r['key'] = $key;
                $config = $r;
                break;
            }
        }
        if (!$config) {
            exception("没有开启电子面单");
        }
        //请选择物流快递
        if (!isset($data['express_id'])) {
            exception("请选择物流快递");
        }
        //请选择物流快递
        if (!isset($data['order_code'])) {
            exception("请输入发货订单号");
        }
        //获取发货人的信息
        $express = new  \addons\electronics\model\Express();
        $express_info = $express->find($data['express_id']);

        if (!$express_info) {
            exception("选择的物流快递不存在");
        }
        //构建电子面单提交信息
        $app = new \addons\electronics\library\Application($config);
        $result = $app->build($express_info->toArray(), $data);
        if ($result) {
            return $result;
        } else {
            exception($app->getError());
        }
    }

    /**
     * 物流信息查询
     * $data['express_code'] 快递单号
     * $data['shipper_code'] 物流商代码【可以为空】
     * @param unknown $data
     */
    public function electronicsQuery($data)
    {
        if (!isset($data['express_code'])) {
            exception("快递单号不能为空");
        }
        $config = array();
        foreach ($this->getConfig() as $key => $r) {
            if ($r['is_open'] == 1) {
                $r['key'] = $key;
                $config = $r;
                break;
            }
        }
        if (!$config) {
            exception("没有启动查询接口");
        }
        //从系统上拿物流快递编码
        if ($data['express_id']) {
            $express = new  \addons\electronics\model\Express();
            $express_info = $express->find($data['express_id']);
            if (isset($express_info['express_code1'])) {
                $data['shipper_code'] = $express_info['express_code1'];
            }
        }
        $data['shipper_code']=isset($data['shipper_code'])?$data['shipper_code']:'';
        $app = new \addons\electronics\library\Application($config);
        $rs = $app->query($data['express_code'], $data['shipper_code']);
        if ($rs) {
            return $rs;
        } else {
            exception($app->getError());
        }
    }
}
