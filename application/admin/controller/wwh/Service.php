<?php

namespace app\admin\controller\wwh;

use app\common\controller\Backend;
use think\Db;

/**
 *
 *
 * @icon fa fa-circle-o
 */
class Service extends Backend
{
    /**
     * 查看
     */
    public function index()
    {
        $data = Db::name('wwh_service')->where('id', 1)->find();
        $this->assign('data', $data);
        return $this->view->fetch();
    }
    
    
    /**
     * 服务策略修改
     */
    public function serviceedit()
    {
        $s=[
            'content'=>input('content'),
        ];
        $test = Db::name('wwh_service')->where('id', 1)->find();
        if (empty($test)) {
            $data = Db::name('wwh_service')->insert($s);
        } else {
            $data = Db::name('wwh_service')->where('id', 1)->setField($s);
        }
        if ($data) {
            $this->success('保存成功');
        } else {
            $this->error('保存失败');
        }
    }
}
