<?php

namespace addons\wwh\controller;

use think\Db;
use think\config;
use think\Paginator;
use think\captcha\Captcha;
use think\Request;
use app\common\library\Email;
use fast\Tree;

class Index extends \think\addons\Controller
{
    /**
     * 首页
     */
    public function index()
    {
        //产品中心分类
        $productcategory = Db::name('wwh_productcategory')->where('pid', 0)->limit(6)->select();
        $this->assign('productcategory', $productcategory);
        //解决方案分类
        $casescategory = Db::name('wwh_casescategory')->where('pid', 0)->limit(6)->select();
        $this->assign('casescategory', $casescategory);
        //新闻中心分类
        $newscategory = Db::name('wwh_newscategory')->where('pid', 0)->limit(6)->select();
        $this->assign('newscategory', $newscategory);
        //站点设置
        $config = Db::name('wwh_config')->where('id', 1)->find();
        if ($config) {
            $this->assign('config', $config);
        } else {
            $this->error("数据不存在");
        }
        
        $banner = Db::name('wwh_banner')->order('sort asc')->limit(6)->select();
        $this->assign('banner', $banner);
        
        $news = Db::name('wwh_news')->where(['tjdata' => ['EQ','1'],])->order('time desc')->limit(4)->select();
        $this->assign('news', $news);

        $product = Db::name('wwh_product')->where(['tjdata' => ['EQ','1'],])->order('createtime desc')->limit(6)->select();
        $this->assign('product', $product);
        
        $home = Db::name('wwh_home')->where('id', 1)->find();
        if ($home) {
            $this->assign('home', $home);
        } else {
            $this->error("数据不存在");
        }
        
        return $this->view->fetch('index');
    }
        
    
    /**
     * 产品中心
     */
    public function product()
    {
        //产品中心分类
        $productcategory = Db::name('wwh_productcategory')->where('pid', 0)->limit(6)->select();
        $this->assign('productcategory', $productcategory);
        //解决方案分类
        $casescategory = Db::name('wwh_casescategory')->where('pid', 0)->limit(6)->select();
        $this->assign('casescategory', $casescategory);
        //新闻中心分类
        $newscategory = Db::name('wwh_newscategory')->where('pid', 0)->limit(6)->select();
        $this->assign('newscategory', $newscategory);
        //站点设置
        $config = Db::name('wwh_config')->where('id', 1)->find();
        if ($config) {
            $this->assign('config', $config);
        } else {
            $this->error("数据不存在");
        }

        //产品分类
        $res = Db::name('wwh_productcategory')->where('pid', 0)->order('id asc')->select();
        $data=[];
        foreach ($res as $k => $v) {
            //2级
            $res2 = Db::name('wwh_productcategory')->where("pid", $v['id'])->select();
            $data2=[];
            foreach ($res2 as $k2 => $v2) {
                //3级
                $res3 = Db::name('wwh_productcategory')->where("pid", $v2['id'])->select();
                $data3=[];
                foreach ($res3 as $k3 => $v3) {
                    $data3[$k3]['id']=$v3['id'];
                    $data3[$k3]['name']=$v3['name'];
                    $data3[$k3]['child']=$v3['name'];
                }
                $data2[$k2]['id']=$v2['id'];
                $data2[$k2]['name']=$v2['name'];
                $data2[$k2]['child']=$data3;
            }
            $data[$k]['id']=$v['id'];
            $data[$k]['name']=$v['name'];
            $data[$k]['child']=$data2;
        }
        $this->assign('name', $data);
        $config = get_addon_config("wwh");
        $prostatus = $config['prostatus'];
        $this->assign('prostatus', $prostatus);

        $id = input('id');
        $this->assign('id', $id);
        if ($id) {
            $data5['list'] = Db::name('wwh_product')->whereRaw('find_in_set('.$id.', `pids`)')->paginate(6, false, ['query' => request()->param()]);
        } else {
            $data5['list'] = Db::name('wwh_product')->paginate(6, false, ['query' => request()->param()]);
        }
        $data5['page'] = $data5['list']->render();
        if (Request::instance()->isAjax()) {
            return json($data5);
        }
        $this->assign('list', $data5['list']);
        $this->assign('page', $data5['page']);

        return $this->view->fetch();
    }


    /**
     * 产品详情
     */
    public function product_detail()
    {
        //产品中心分类
        $productcategory = Db::name('wwh_productcategory')->where('pid', 0)->limit(6)->select();
        $this->assign('productcategory', $productcategory);
        //解决方案分类
        $casescategory = Db::name('wwh_casescategory')->where('pid', 0)->limit(6)->select();
        $this->assign('casescategory', $casescategory);
        //新闻中心分类
        $newscategory = Db::name('wwh_newscategory')->where('pid', 0)->limit(6)->select();
        $this->assign('newscategory', $newscategory);
        //站点设置
        $config = Db::name('wwh_config')->where('id', 1)->find();
        if ($config) {
            $this->assign('config', $config);
        } else {
            $this->error("数据不存在");
        }

        $id=input('id');
        if ($id) {
            $data = Db::name('wwh_product')->find($id);
            $data['lunbo'] = explode(',', $data['banner_images']);
            $this->assign('data', $data);
        } else {
            $this->error("未获取到产品ID");
        }

        return $this->view->fetch();
    }
    
    
    /**
     * 解决方案
     */
    public function cases()
    {
        //产品中心分类
        $productcategory = Db::name('wwh_productcategory')->where('pid', 0)->limit(6)->select();
        $this->assign('productcategory', $productcategory);
        //解决方案分类
        $casescategory = Db::name('wwh_casescategory')->where('pid', 0)->limit(6)->select();
        $this->assign('casescategory', $casescategory);
        //新闻中心分类
        $newscategory = Db::name('wwh_newscategory')->where('pid', 0)->limit(6)->select();
        $this->assign('newscategory', $newscategory);
        //站点设置
        $config = Db::name('wwh_config')->where('id', 1)->find();
        if ($config) {
            $this->assign('config', $config);
        } else {
            $this->error("数据不存在");
        }

        $id = input('id');
        $this->assign('id', $id);
        if ($id) {
            $data5['list'] = Db::name('wwh_cases')->where('casescategoryid', $id)->paginate(6, false, ['query' => request()->param()]);
        } else {
            $data5['list'] = Db::name('wwh_cases')->paginate(6, false, ['query' => request()->param()]);
        }
        $data5['page'] = $data5['list']->render();
        if (Request::instance()->isAjax()) {
            return json($data5);
        }
        $this->assign('list', $data5['list']);
        $this->assign('page', $data5['page']);

        return $this->view->fetch();
    }
    
    
    /**
     * 解决方案详情
     */
    public function cases_detail()
    {
        //产品中心分类
        $productcategory = Db::name('wwh_productcategory')->where('pid', 0)->limit(6)->select();
        $this->assign('productcategory', $productcategory);
        //解决方案分类
        $casescategory = Db::name('wwh_casescategory')->where('pid', 0)->limit(6)->select();
        $this->assign('casescategory', $casescategory);
        //新闻中心分类
        $newscategory = Db::name('wwh_newscategory')->where('pid', 0)->limit(6)->select();
        $this->assign('newscategory', $newscategory);
        //站点设置
        $config = Db::name('wwh_config')->where('id', 1)->find();
        if ($config) {
            $this->assign('config', $config);
        } else {
            $this->error("数据不存在");
        }
        
        $id=input('id');
        if ($id) {
            $data = Db::name('wwh_cases')->find($id);
            $front=Db::name('wwh_cases')->where('id', '>', $id)->order('id asc')->limit('1')->find();   //上一篇
            $after=Db::name('wwh_cases')->where('id', '<', $id)->order('id desc')->limit('1')->find();    //下一篇
            Db::name('wwh_cases')->where('id', '=', $id)->setInc('views');   //自增浏览数
            $this->assign('data', $data);
            $this->assign('front', $front);
            $this->assign('after', $after);
        } else {
            $this->error("未获取到解决方案ID");
        }

        return $this->view->fetch();
    }

    
    /**
     * 服务策略
     */
    public function service()
    {
        //产品中心分类
        $productcategory = Db::name('wwh_productcategory')->where('pid', 0)->limit(6)->select();
        $this->assign('productcategory', $productcategory);
        //解决方案分类
        $casescategory = Db::name('wwh_casescategory')->where('pid', 0)->limit(6)->select();
        $this->assign('casescategory', $casescategory);
        //新闻中心分类
        $newscategory = Db::name('wwh_newscategory')->where('pid', 0)->limit(6)->select();
        $this->assign('newscategory', $newscategory);
        //站点设置
        $config = Db::name('wwh_config')->where('id', 1)->find();
        if ($config) {
            $this->assign('config', $config);
        } else {
            $this->error("数据不存在");
        }
        
        $data = Db::name('wwh_service')->where('id', 1)->find();
        if ($data) {
            $this->assign('data', $data);
        } else {
            $this->error("请导入测试数据");
        }

        return $this->view->fetch();
    }
    
    
    /**
     * 营销网络
     */
    public function market()
    {
        //产品中心分类
        $productcategory = Db::name('wwh_productcategory')->where('pid', 0)->limit(6)->select();
        $this->assign('productcategory', $productcategory);
        //解决方案分类
        $casescategory = Db::name('wwh_casescategory')->where('pid', 0)->limit(6)->select();
        $this->assign('casescategory', $casescategory);
        //新闻中心分类
        $newscategory = Db::name('wwh_newscategory')->where('pid', 0)->limit(6)->select();
        $this->assign('newscategory', $newscategory);
        //站点设置
        $config = Db::name('wwh_config')->where('id', 1)->find();
        if ($config) {
            $this->assign('config', $config);
        } else {
            $this->error("数据不存在");
        }
        
        $data = Db::name('wwh_market')->select();
        if ($data) {
            $this->assign('data', $data);
        } else {
            $this->error("请导入测试数据");
        }
        return $this->view->fetch();
    }


    /**
     * 资料下载
     */
    public function download()
    {
        //产品中心分类
        $productcategory = Db::name('wwh_productcategory')->where('pid', 0)->limit(6)->select();
        $this->assign('productcategory', $productcategory);
        //解决方案分类
        $casescategory = Db::name('wwh_casescategory')->where('pid', 0)->limit(6)->select();
        $this->assign('casescategory', $casescategory);
        //新闻中心分类
        $newscategory = Db::name('wwh_newscategory')->where('pid', 0)->limit(6)->select();
        $this->assign('newscategory', $newscategory);
        //站点设置
        $config = Db::name('wwh_config')->where('id', 1)->find();
        if ($config) {
            $this->assign('config', $config);
        } else {
            $this->error("数据不存在");
        }

        //1级
        $res = Db::name('wwh_downloadcategory')->where('pid', 0)->order('id asc')->select();
        $data=[];
        foreach ($res as $k => $v) {
            //2级
            $res2 = Db::name('wwh_downloadcategory')->where("pid", $v['id'])->select();
            $data2=[];
            foreach ($res2 as $k2 => $v2) {
                //3级
                $res3 = Db::name('wwh_downloadcategory')->where("pid", $v2['id'])->select();
                $data3=[];
                foreach ($res3 as $k3 => $v3) {
                    $data3[$k3]['id']=$v3['id'];
                    $data3[$k3]['name']=$v3['name'];
                    $data3[$k3]['child']=$v3['name'];
                }
                $data2[$k2]['id']=$v2['id'];
                $data2[$k2]['name']=$v2['name'];
                $data2[$k2]['child']=$data3;
            }
            $data[$k]['id']=$v['id'];
            $data[$k]['name']=$v['name'];
            $data[$k]['child']=$data2;
        }

        $keywords = input('keywords');
        $category = input('category');
        if (empty($keywords || $category)) {
            $data5['list'] = Db::name('wwh_download')->order('weigh desc')->paginate(6, false, ['query' => request()->param()]);
        } else {
            $map1['downloadname'] = ['like','%'.$keywords.'%'];
            $map2['downloadcategoryid'] = ['like','%'.$category.'%'];
            $data5['list'] = Db::name('wwh_download')->where($map1)->where($map2)->order('weigh desc')->paginate(6, false, ['query' => request()->param()]);
        }
        $data5['page'] = $data5['list']->render();
        if (Request::instance()->isAjax()) {
            return json($data5);
        }
        $this->assign('list', $data5['list']);
        $this->assign('page', $data5['page']);
        $this->assign('name', $data);

        return $this->view->fetch();
    }


    /**
     * 新闻中心
     */
    public function news()
    {
        //产品中心分类
        $productcategory = Db::name('wwh_productcategory')->where('pid', 0)->limit(6)->select();
        $this->assign('productcategory', $productcategory);
        //解决方案分类
        $casescategory = Db::name('wwh_casescategory')->where('pid', 0)->limit(6)->select();
        $this->assign('casescategory', $casescategory);
        //新闻中心分类
        $newscategory = Db::name('wwh_newscategory')->where('pid', 0)->limit(6)->select();
        $this->assign('newscategory', $newscategory);
        //站点设置
        $config = Db::name('wwh_config')->where('id', 1)->find();
        if ($config) {
            $this->assign('config', $config);
        } else {
            $this->error("数据不存在");
        }

        $id = input('id');
        $this->assign('id', $id);
        if ($id) {
            $data5['list'] = Db::name('wwh_news')->where('newscategoryid', $id)->paginate(6, false, ['query' => request()->param()]);
        } else {
            $data5['list'] = Db::name('wwh_news')->paginate(6, false, ['query' => request()->param()]);
        }
        $data5['page'] = $data5['list']->render();
        if (Request::instance()->isAjax()) {
            return json($data5);
        }
        $this->assign('list', $data5['list']);
        $this->assign('page', $data5['page']);

        //热门文章
        $news2 = Db::name('wwh_news')->order('views desc')->limit(4)->select();
        $this->assign('news2', $news2);

        return $this->view->fetch();
    }
    
    
    /**
     * 新闻详情
     */
    public function news_detail()
    {
        //产品中心分类
        $productcategory = Db::name('wwh_productcategory')->where('pid', 0)->limit(6)->select();
        $this->assign('productcategory', $productcategory);
        //解决方案分类
        $casescategory = Db::name('wwh_casescategory')->where('pid', 0)->limit(6)->select();
        $this->assign('casescategory', $casescategory);
        //新闻中心分类
        $newscategory = Db::name('wwh_newscategory')->where('pid', 0)->limit(6)->select();
        $this->assign('newscategory', $newscategory);
        //站点设置
        $config = Db::name('wwh_config')->where('id', 1)->find();
        if ($config) {
            $this->assign('config', $config);
        } else {
            $this->error("数据不存在");
        }
        
        $id=input('id');
        if ($id) {
            $data = Db::name('wwh_news')->find($id);
            $front=Db::name('wwh_news')->where('id', '>', $id)->order('id asc')->limit('1')->find();   //上一篇
            $after=Db::name('wwh_news')->where('id', '<', $id)->order('id desc')->limit('1')->find();    //下一篇
            Db::name('wwh_news')->where('id', '=', $id)->setInc('views');   //自增浏览数

            $this->assign('data', $data);
            $this->assign('front', $front);
            $this->assign('after', $after);
        } else {
            $this->error("未获取到新闻详情ID");
        }
        $news2 = Db::name('wwh_news')->where(['tjdata' => ['EQ','1'],])->order('views desc')->limit(4)->select();
        $this->assign('news2', $news2);
        return $this->view->fetch();
    }
    
    
    /**
     * 公司概况
     */
    public function about()
    {
        //产品中心分类
        $productcategory = Db::name('wwh_productcategory')->where('pid', 0)->limit(6)->select();
        $this->assign('productcategory', $productcategory);
        //解决方案分类
        $casescategory = Db::name('wwh_casescategory')->where('pid', 0)->limit(6)->select();
        $this->assign('casescategory', $casescategory);
        //新闻中心分类
        $newscategory = Db::name('wwh_newscategory')->where('pid', 0)->limit(6)->select();
        $this->assign('newscategory', $newscategory);
        //站点设置
        $config = Db::name('wwh_config')->where('id', 1)->find();
        if ($config) {
            $this->assign('config', $config);
        } else {
            $this->error("数据不存在");
        }

        $data = Db::name('wwh_about')->where('id', 1)->find();
        if ($data) {
            $this->assign('data', $data);
        } else {
            $this->error("请导入测试数据");
        }

        $development = Db::name('wwh_development')->order('id asc')->select();
        if ($development) {
            $this->assign('development', $development);
        } else {
            $this->error("请添加发展历程数据");
        }

        return $this->view->fetch();
    }


    /**
     * 荣誉资质
     */
    public function honor()
    {
        //产品中心分类
        $productcategory = Db::name('wwh_productcategory')->where('pid', 0)->limit(6)->select();
        $this->assign('productcategory', $productcategory);
        //解决方案分类
        $casescategory = Db::name('wwh_casescategory')->where('pid', 0)->limit(6)->select();
        $this->assign('casescategory', $casescategory);
        //新闻中心分类
        $newscategory = Db::name('wwh_newscategory')->where('pid', 0)->limit(6)->select();
        $this->assign('newscategory', $newscategory);
        //站点设置
        $config = Db::name('wwh_config')->where('id', 1)->find();
        if ($config) {
            $this->assign('config', $config);
        } else {
            $this->error("数据不存在");
        }

        $data5['list'] = Db::name('wwh_honor')->order('sort desc')->paginate(8, false, ['query' => request()->param()]);
        $data5['page'] = $data5['list']->render();
        if (Request::instance()->isAjax()) {
            return json($data5);
        }
        $this->assign('list', $data5['list']);
        $this->assign('page', $data5['page']);

        return $this->view->fetch();
    }


    /**
     * 加入我们
     */
    public function join()
    {
        //产品中心分类
        $productcategory = Db::name('wwh_productcategory')->where('pid', 0)->limit(6)->select();
        $this->assign('productcategory', $productcategory);
        //解决方案分类
        $casescategory = Db::name('wwh_casescategory')->where('pid', 0)->limit(6)->select();
        $this->assign('casescategory', $casescategory);
        //新闻中心分类
        $newscategory = Db::name('wwh_newscategory')->where('pid', 0)->limit(6)->select();
        $this->assign('newscategory', $newscategory);
        //站点设置
        $config = Db::name('wwh_config')->where('id', 1)->find();
        if ($config) {
            $this->assign('config', $config);
        } else {
            $this->error("数据不存在");
        }

        $keywords = input('keywords');
        $branch = input('branch');
        $address = input('address');
        if (empty($keywords || $branch || $address)) {
            $data5['list'] = Db::name('wwh_position')->order('time desc')->paginate(6, false, ['query' => request()->param()]);
        } else {
            $map1['name'] = ['like','%'.$keywords.'%'];
            $map2['dept'] = ['like','%'.$branch.'%'];
            $map3['addr'] = ['like','%'.$address.'%'];
            $data5['list'] = Db::name('wwh_position')->where($map1)->where($map2)->where($map3)->order('time desc')->paginate(6, false, ['query' => request()->param()]);
        }
        $data5['page'] = $data5['list']->render();
        if (Request::instance()->isAjax()) {
            return json($data5);
        }
        $this->assign('list', $data5['list']);
        $this->assign('page', $data5['page']);

        $dept = Db::name('wwh_position')->distinct(true)->field('dept')->select();
        $addr = Db::name('wwh_position')->distinct(true)->field('addr')->select();
        $total = $data5['list']->total();
        $this->assign('dept', $dept);
        $this->assign('addr', $addr);
        $this->assign('total', $total);

        return $this->view->fetch();
    }
    
    
    /**
     * 联系我们
     */
    public function contact()
    {
        //产品中心分类
        $productcategory = Db::name('wwh_productcategory')->where('pid', 0)->limit(6)->select();
        $this->assign('productcategory', $productcategory);
        //解决方案分类
        $casescategory = Db::name('wwh_casescategory')->where('pid', 0)->limit(6)->select();
        $this->assign('casescategory', $casescategory);
        //新闻中心分类
        $newscategory = Db::name('wwh_newscategory')->where('pid', 0)->limit(6)->select();
        $this->assign('newscategory', $newscategory);
        //站点设置
        $config = Db::name('wwh_config')->where('id', 1)->find();
        if ($config) {
            $this->assign('config', $config);
        } else {
            $this->error("数据不存在");
        }
        
        $data = Db::name('wwh_contact')->where('id', 1)->find();
        if ($data) {
            $this->assign('data', $data);
        } else {
            $this->error("请导入测试数据");
        }
        
        return $this->view->fetch();
    }
    
    
    /**
     * 客户留言
     */
    public function message()
    {
        // 获取ajax请求的值
        $verify = input("verify");
        // 进行验证码的验证
        if (!captcha_check($verify)) {
            $this->error('验证码不正确');
        } else {
            $realname = input("realname");
            $company = input("company");
            $tel = input("tel");
            $email = input("email");
            $content = input("content");
            $data =['realname'=>"$realname",'company'=>"$company",'tel'=>"$tel",'email'=>"$email",'content'=>"$content"];

            $html = "<p><strong>公司名称：</strong>$company</p>
                     <p><strong>姓名：</strong>$realname</p>
                     <p><strong>邮箱：</strong>$email</p>
                     <p><strong>电话：</strong>$tel</p>
                     <p><strong>内容：</strong>$content</p>";

            $config = get_addon_config("wwh");
            $receive = $config['receive'];

            $obj = \app\common\library\Email::instance();
            $result = $obj
                ->to($receive)
                ->subject('客户留言')
                ->message($html)
                ->send();
            if ($result) {
                $db = Db::name('wwh_message')->insert($data);
                $this->assign('db', $db);
                $mes = 1;
            } elseif (!$result) {
                $mes = 2;
            } else {
                $mes = 3;
            }
        }
        return json($mes);
    }
  
    
    /**
     * 验证码
     */
    public function verify()
    {
        $captcha = new \think\captcha\Captcha();
        //验证码过期时间（s）
        $captcha->expire =1800;
        //验证码位数
        $captcha->length = 4;
        //验证成功后是否重置
        $captcha->reset = true;
        return $captcha->entry();
    }
    
    
    /**
     * 搜索页面
     */
    public function search()
    {
        //产品中心分类
        $productcategory = Db::name('wwh_productcategory')->where('pid', 0)->limit(6)->select();
        $this->assign('productcategory', $productcategory);
        //解决方案分类
        $casescategory = Db::name('wwh_casescategory')->where('pid', 0)->limit(6)->select();
        $this->assign('casescategory', $casescategory);
        //新闻中心分类
        $newscategory = Db::name('wwh_newscategory')->where('pid', 0)->limit(6)->select();
        $this->assign('newscategory', $newscategory);
        //站点设置
        $config = Db::name('wwh_config')->where('id', 1)->find();
        if ($config) {
            $this->assign('config', $config);
        } else {
            $this->error("数据不存在");
        }

        $keywords = input('keywords');
        $this->assign('keywords', $keywords);
        if ($keywords) {
            $map['newsname'] = ['like','%'.$keywords.'%'];
            $data5['list'] = Db::name('wwh_news')->where($map)->paginate(6, false, ['query' => request()->param()]);
        } else {
            $data5['list'] = Db::name('wwh_news')->paginate(6, false, ['query' => request()->param()]);
        }
        $data5['page'] = $data5['list']->render();
        if (Request::instance()->isAjax()) {
            return json($data5);
        }
        $this->assign('list', $data5['list']);
        $this->assign('page', $data5['page']);

        $news2 = Db::name('wwh_news')->where(['tjdata' => ['EQ','1'],])->order('views desc')->limit(4)->select();
        $this->assign('news2', $news2);

        return $this->view->fetch();
    }
}
