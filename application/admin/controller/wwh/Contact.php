<?php

namespace app\admin\controller\wwh;

use app\common\controller\Backend;
use think\Db;

/**
 *
 *
 * @icon fa fa-circle-o
 */
class Contact extends Backend
{
    /**
     * 查看
     */
    public function index()
    {
        $data = Db::name('wwh_contact')->where('id', 1)->find();
        $this->assign('data', $data);
        return $this->view->fetch();
    }
    
    
    /**
     * 联系我们修改
     */
    public function conedit()
    {
        $s=[
            'tel'=>input('tel'),
            'fax'=>input('fax'),
            'email'=>input('email'),
            'time'=>input('time'),
            'address'=>input('address'),
        ];
        $test = Db::name('wwh_contact')->where('id', 1)->find();
        if (empty($test)) {
            $data = Db::name('wwh_contact')->insert($s);
        } else {
            $data = Db::name('wwh_contact')->where('id', 1)->setField($s);
        }
        if ($data) {
            $this->success('保存成功');
        } else {
            $this->error('保存失败');
        }
    }
}
