<?php

namespace app\admin\controller\wwh;

use app\common\controller\Backend;
use think\Db;

/**
 *
 *
 * @icon fa fa-circle-o
 */
class About extends Backend
{
    /**
     * 查看
     */
    public function index()
    {
        $data = Db::name('wwh_about')->where('id', 1)->find();
        $this->assign('data', $data);
        return $this->view->fetch();
    }
    
    
    /**
     * 企业介绍修改
     */
    public function about()
    {
        $s=[
            'about_title'=>input('about_title'),
            'about_description'=>input('about_description'),
            'about_content'=>input('about_content'),
        ];
        $test = Db::name('wwh_about')->where('id', 1)->find();
        if (empty($test)) {
            $data = Db::name('wwh_about')->insert($s);
        } else {
            $data = Db::name('wwh_about')->where('id', 1)->setField($s);
        }
        if ($data) {
            $this->success('保存成功');
        } else {
            $this->error('保存失败');
        }
    }
    
    
    /**
     * 企业文化修改
     */
    public function culture()
    {
        $s=[
            'culture_title1'=>input('culture_title1'),
            'culture_en1'=>input('culture_en1'),
            'culture_des1'=>input('culture_des1'),
            'culture_title2'=>input('culture_title2'),
            'culture_en2'=>input('culture_en2'),
            'culture_title3'=>input('culture_title3'),
            'culture_en3'=>input('culture_en3'),
            'culture_title4'=>input('culture_title4'),
            'culture_en4'=>input('culture_en4'),
            'culture_title5'=>input('culture_title5'),
            'culture_en5'=>input('culture_en5'),
        ];
        $test = Db::name('wwh_about')->where('id', 1)->find();
        if (empty($test)) {
            $data = Db::name('wwh_about')->insert($s);
        } else {
            $data = Db::name('wwh_about')->where('id', 1)->setField($s);
        }
        if ($data) {
            $this->success('保存成功');
        } else {
            $this->error('保存失败');
        }
    }
}
