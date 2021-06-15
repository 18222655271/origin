<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:61:"D:\phpstudy_pro\WWW\faas\addons\ykquest\view\index\index.html";i:1588838593;s:63:"D:\phpstudy_pro\WWW\faas\addons\ykquest\view\common\header.html";i:1588838593;s:63:"D:\phpstudy_pro\WWW\faas\addons\ykquest\view\common\footer.html";i:1588838593;}*/ ?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>问卷调查</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no"/>
        <meta content="yes" name="apple-mobile-web-app-capable">
        <meta content="yes" name="apple-touch-fullscreen">
        <link rel="shortcut icon" href="/assets/img/favicon.ico" />
        <link href="/assets/css/frontend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">
        <link href="/assets/css/user.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">
        <link rel="stylesheet" href="/assets/addons/ykquest/css/base.css">
        <link rel="stylesheet" href="/assets/addons/ykquest/css/style.css">
        <script src="/assets/addons/ykquest/js/jquery.js"></script>
    </head>
    <body>
        <header class="header">
            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#header-navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="<?php echo addon_url('ykquest/index/index'); ?>" style="padding:6px 15px;"><img src="/assets/img/logo.png" style="height:40px;" alt=""></a>
                    </div>
                    <div class="collapse navbar-collapse" id="header-navbar">
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <?php if($user): ?>
                                <a href="<?php echo url('user/index'); ?>" class="dropdown-toggle" data-toggle="dropdown" style="padding-top: 10px;height: 50px;">
                                    <span class="avatar-img"><img src="<?php echo cdnurl($user['avatar']); ?>" alt=""></span>
                                </a>
                                <?php else: ?>
                                <a href="<?php echo url('user/index'); ?>" class="dropdown-toggle" data-toggle="dropdown">会员中心 <b class="caret"></b></a>
                                <?php endif; ?>
                                <ul class="dropdown-menu">
                                    <?php if($user): ?>
                                    <li><a href="<?php echo url('index/user/index','', false, \think\Config::get('url_domain_deploy')?'www':''); ?>"><i class="fa fa-user-circle fa-fw"></i>个人中心</a></li>
                                    <li><a href="<?php echo url('index/user/profile','', false, \think\Config::get('url_domain_deploy')?'www':''); ?>"><i class="fa fa-user-o fa-fw"></i><?php echo __('Profile'); ?></a></li>
                                    <li><a href="<?php echo url('index/user/changepwd','', false, \think\Config::get('url_domain_deploy')?'www':''); ?>"><i class="fa fa-key fa-fw"></i>修改密码</a></li>
                                    <li><a href="<?php echo url('index/user/logout','', false, \think\Config::get('url_domain_deploy')?'www':''); ?>"><i class="fa fa-sign-out fa-fw"></i><?php echo __('Sign out'); ?></a></li>
                                    <?php else: ?>
                                    <li><a href="<?php echo url('index/user/login', '', false, \think\Config::get('url_domain_deploy')?'www':''); ?>"><i class="fa fa-sign-in fa-fw"></i> <?php echo __('Sign in'); ?></a></li>
                                    <li><a href="<?php echo url('index/user/register', '', false, \think\Config::get('url_domain_deploy')?'www':''); ?>"><i class="fa fa-user-o fa-fw"></i> <?php echo __('Sign up'); ?></a></li>
                                    <?php endif; ?>

                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

<div class="wjlist">
    <div class="wjlist-content">
        <?php if(count($list)>0): if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>
        <div class="wjlist-row">
            <a href="<?php echo addon_url('ykquest/index/detail',array('id'=>$val['id'])); ?>">
                <div class="wjlist-row-cont">
                    <div class="wjlist-row-title f14"><?php echo $val['name']; ?></div>
                    <div class="wjlist-row-top"></div>
                    <div class="wjlist-row-start f12">
                        开始： <?php echo date('Y-m-d',$val['starttime']); ?>
                    </div>

                </div>
            </a>
        </div>

        <?php endforeach; endif; else: echo "" ;endif; else: ?>
        <div class="anIser">
            <div>
                <image src="/assets/addons/ykquest/image/null.png">
            </div>
            <div>
                暂无问卷
            </div>
        </div>
        <?php endif; ?>
    </div>
    <div class="wjlist-end">
    </div>
    <div class="wjlist-page" id="page">
        <?php echo $page; ?>
    </div>
    <div class="wjlist-end">
    </div>
</div>
<div class="footer">Copyright © 2017-2020</div>
</body>
</html>
