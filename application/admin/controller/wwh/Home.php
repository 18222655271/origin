<?php

namespace app\admin\controller\wwh;

use app\common\controller\Backend;
use think\Db;

/**
 *
 *
 * @icon fa fa-circle-o
 */
class Home extends Backend
{
    /**
     * 查看
     */
    public function index()
    {
        $data = Db::name('wwh_home')->where('id', 1)->find();
        $this->assign('data', $data);
        return $this->view->fetch();
    }
    
    
    /**
     * 关于我们修改
     */
    public function enterprise()
    {
        $s=[
            'about_title'=>input('about_title'),
            'introduction'=>input('introduction'),
            'title1'=>input('title1'),
            'description1'=>input('description1'),
            'title2'=>input('title2'),
            'description2'=>input('description2'),
            'title3'=>input('title3'),
            'description3'=>input('description3'),
        ];
        $test = Db::name('wwh_home')->where('id', 1)->find();
        if (empty($test)) {
            $data = Db::name('wwh_home')->insert($s);
        } else {
            $data = Db::name('wwh_home')->where('id', 1)->setField($s);
        }
        if ($data) {
            $this->success('保存成功');
        } else {
            $this->error('保存失败');
        }
    }
}
