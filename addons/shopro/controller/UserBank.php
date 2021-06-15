<?php

namespace addons\shopro\controller;


class UserBank extends Base
{

    protected $noNeedLogin = [];
    protected $noNeedRight = ['*'];


    // 获取提现账户信息
    public function info()
    {
        $type = $this->request->get('type');
        $this->success('提现账户', \addons\shopro\model\UserBank::info($type));
    }


    public function edit()
    {
        $params = $this->request->post();
        if ($params['type'] === 'alipay') {
            $params['bank_name'] = '支付宝账户';
        }

        // 表单验证
        $this->shoproValidate($params, get_class(), 'edit');

        $this->success('编辑成功', \addons\shopro\model\UserBank::edit($params));
    }
}
