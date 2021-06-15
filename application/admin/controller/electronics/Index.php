<?php

namespace app\admin\controller\electronics;

use app\common\controller\Backend;
use think\Exception;
use think\Validate;

/**
 *电子面单
 */
class Index extends Backend
{

    protected $noNeedRight = ['selectpage'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\electronics\Express();
    }

    /**
     * 快递物流
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return parent::selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->where($where)
                ->order($sort, $order)->fetchSql(false)
                ->count();
            $list = $this->model
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 选择
     * @return string|\think\response\Json
     * @internal
     */
    public function selectpage()
    {
        return $this->index();
    }

    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isAjax()) {
            $params = $this->request->post("row/a");
            $data = [
                'city_id' => $this->request->param('city'),
                'province_id' => $this->request->param('province'),
                'region_id' => $this->request->param('area'),
                'create_time' => time(),
            ];
            $params = array_merge($data, $params);
            $validate = validate('app\\admin\\validate\\electronics\\Express');
            $result = $validate->check($params);
            if (!$result) {
                $this->error($validate->getError());
            }

            //判断是否纯在
            $info = $this->model->where('express_name', $params['express_name'])
                ->find();
            if ($info) {
                $this->error($params['express_name'] . "》已经存在");
            }
            try {

                $this->model->save($params);

            } catch (\Exception $e) {

                $this->error($e->getMessage());
            }
            $this->success("成功");
        }
        return $this->view->fetch();
    }

    /**
     * 修改
     * @param string $ids
     * @return string
     */
    public function edit($ids = NULL)
    {
        $row = $this->model->get($ids);
        if ($this->request->isAjax()) {
            $params = $this->request->post("row/a");
            $data = [
                'city_id' => $this->request->param('city'),
                'province_id' => $this->request->param('province'),
                'region_id' => $this->request->param('area'),
                'create_time' => time(),
            ];
            $params = array_merge($data, $params);

            $validate = validate('app\\admin\\validate\\electronics\\Express');
            $result = $validate->check($params);
            if (!$result) {
                $this->error($validate->getError());
            }
            //判断是否纯在
            $info = $this->model->where('id', '<>', $row->id)->where('express_name', $params['express_name'])
                ->fetchSql(false)->find();
            if ($info) {
                $this->error($params['express_name'] . "》已经存在");
            }
            try {
                $this->model->save($params, ['id' => $row->id]);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
            $this->success("成功");
        }
        $this->view->assign('row', $row);
        return $this->view->fetch();
    }

    /**
     * 删除
     * @param null $ids
     */
    public function del($ids = null)
    {
        return $this->del($ids);
    }

    /**
     * 发货演示代码
     */
    public function send()
    {
        if ($this->request->isAjax()) {
            $params = $this->request->post("row/a");
            //TODO 根据你商城订单号获取订单信息

            //如果物流单号为空说明是电子面单发货
            if (!$params['express_code']) {
                $data['express_id'] = $params['express_id'];
                $data['order_code'] = $params['order_code'];
                $data['name'] = $params['name'];//
                $data['mobile'] = $params['mobile'];//收货人手机
                $data['province'] = "广东省";//可以是fastadmin area表的id
                $data['city'] = "广州市";//可以是fastadmin area表的id
                $data['area'] = "白云区";//可以是fastadmin area表的id
                $data['address'] = "详细地址";
                $data['goods_name'] = "产品名称";
                try {
                    /*
                     * 电子面单构建
                     * @param $express_id array 物流快递的信息ID
                     * @param $data array order_code:商城的订单号 name:收货人姓名 mobile：收货人手机 province：省份
                     * city：城市 area：区域 address：详细地址  goods_name：产品信息
                     * @return array
                     * Order['LogisticCode']:快递单号，
                     * PrintTemplate：面单打印模板内容
                     * 其他返回参数可以查看http://www.kdniao.com/file/%E5%BF%AB%E9%80%92%E9%B8%9F%E6%8E%A5%E5%8F%A3%E6%8A%80%E6%9C%AF%E6%96%87%E6%A1%A3v5.21.pdf
                     */
                    $res = \think\Hook::listen('electronics_build', $data, null, true);
                    //TODO保存订单发货信息，修改订单发货状态

                    $this->success("成功", '', $res);
                } catch (Exception $e) {
                    $this->error($e->getMessage());
                }
            }
            //TODO保存订单发货信息，修改订单发货状态
            $this->error("当前是手动输入物流订单号，不生成电子面单");

        } else {
            return $this->view->fetch();
        }

    }

    /**
     * 物流查询
     * @return string
     * @throws Exception
     */
    public function query()
    {
        if ($this->request->isAjax()) {
            $express_code = $this->request->param('express_code');
            $express_id = $this->request->param('express_id');
            if (!$express_code) {
                $this->error("请输入物流单号");
            }
            try {
                $param = ['express_code' => $express_code, 'express_id' => $express_id];
                $res = \think\Hook::listen('electronics_query', $param, null, true);
                $res = json_decode($res, true);
                $total = isset($res['data']) ? count($res['data']) : 0;
                $result = array("total" => $total, "rows" => isset($res['data']) ? $res['data'] : []);
                return json($result);

            } catch (Exception $e) {
                $this->error($e->getMessage());
            }
        } else {
            return $this->view->fetch('', ['express_id' => $this->request->param('express_id'), 'express_code' => $this->request->param('express_code')]);
        }

    }
}
