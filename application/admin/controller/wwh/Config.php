<?php

namespace app\admin\controller\wwh;

use app\common\controller\Backend;
use think\Db;

/**
 *
 *
 * @icon fa fa-circle-o
 */
class Config extends Backend
{
    /**
     * 查看
     */
    public function index()
    {
        $data = Db::name('wwh_config')->where('id', 1)->find();
        $this->assign('data', $data);
        return $this->view->fetch();
    }
    
    
    /**
     * 站点设置修改
     */
    public function configedit()
    {
        $s=[
            'site_name'=>input('site_name'),
            'keywords'=>input('keywords'),
            'description'=>input('description'),
			'logo'=>input('logo'),
            'gongwang'=>input('gongwang'),
            'beian'=>input('beian'),
            'copyright'=>input('copyright'),
            'image'=>input('image'),
            'email'=>input('email'),
        ];
        $test = Db::name('wwh_config')->where('id', 1)->find();
        if (empty($test)) {
            $data = Db::name('wwh_config')->insert($s);
        } else {
            $data = Db::name('wwh_config')->where('id', 1)->setField($s);
        }
        if ($data) {
            $this->success('保存成功');
        } else {
            $this->error('保存失败');
        }
    }


    /**
     * 栏目Banner修改
     */
    public function banneredit()
    {
        $s=[
            'banner1'=>input('banner1'),
            'banner2'=>input('banner2'),
            'banner3'=>input('banner3'),
            'banner4'=>input('banner4'),
            'banner5'=>input('banner5'),
        ];
        $test = Db::name('wwh_config')->where('id', 1)->find();
        if (empty($test)) {
            $data = Db::name('wwh_config')->insert($s);
        } else {
            $data = Db::name('wwh_config')->where('id', 1)->setField($s);
        }
        if ($data) {
            $this->success('保存成功');
        } else {
            $this->error('保存失败');
        }
    }


    /**
     * 底部链接修改
     */
    public function footeredit()
    {
        $s=[
            'content'=>input('content'),
        ];
        $test = Db::name('wwh_config')->where('id', 1)->find();
        if (empty($test)) {
            $data = Db::name('wwh_config')->insert($s);
        } else {
            $data = Db::name('wwh_config')->where('id', 1)->setField($s);
        }
        if ($data) {
            $this->success('保存成功');
        } else {
            $this->error('保存失败');
        }
    }
}
