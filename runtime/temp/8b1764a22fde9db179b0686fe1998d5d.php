<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:62:"D:\phpstudy_pro\WWW\faas\addons\ykquest\view\index\detail.html";i:1588838593;s:63:"D:\phpstudy_pro\WWW\faas\addons\ykquest\view\common\header.html";i:1588838593;s:63:"D:\phpstudy_pro\WWW\faas\addons\ykquest\view\common\footer.html";i:1588838593;}*/ ?>
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

<div class="wjcontent">
    <form  method="post" name="myform" action="<?php echo addon_url('ykquest/index/Answer'); ?>" onsubmit="return check()">
        <?php if($isAnser): ?>
        <div class="wj-title f18">
            <?php echo $suery['name']; ?>
            <input name="survey_id" value="<?php echo $suery['id']; ?>" type="hidden">
        </div>
        <div class="wj-des f16">  
            <?php echo $suery['description']; ?>
            <div class="wj-top"> </div>
            <div class="wj-promble">
                <?php if(is_array($proList) || $proList instanceof \think\Collection || $proList instanceof \think\Paginator): $k = 0; $__LIST__ = $proList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($k % 2 );++$k;?>
                <div class="wj-promble-list">
                    <div class="wj-pro-title f16">
                        <?php echo $k; ?>.<?php echo $val['title']; ?>

                    </div>
                    <?php if($val['option_type']==0): ?>
                    <div class="wj-option f14">
                        <?php if(is_array($val['oplist']) || $val['oplist'] instanceof \think\Collection || $val['oplist'] instanceof \think\Paginator): $i = 0; $__LIST__ = $val['oplist'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$toption): $mod = ($i % 2 );++$i;?>
                        <div class="wj-rad">
                            <input type="radio" value="<?php echo $toption['id']; ?>" name="row[<?php echo $val['id']; ?>]"  onblur="isNull(this)"> <span ><?php echo $toption['content']; ?></span>
                        </div>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                        <div class="mes">
                            <span></span>
                        </div>
                    </div>
                    <?php elseif($val['option_type']==1): ?>
                    <div class="wj-option f14">
                        <?php if(is_array($val['oplist']) || $val['oplist'] instanceof \think\Collection || $val['oplist'] instanceof \think\Paginator): $i = 0; $__LIST__ = $val['oplist'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$toption): $mod = ($i % 2 );++$i;?>
                        <div class="wj-ck">
                            <input type="checkbox" name="row[<?php echo $val['id']; ?>][]" value="<?php echo $toption['id']; ?>"  /> <span><?php echo $toption['content']; ?></span>
                        </div>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                        <div class="mes">
                            <span></span>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="wj-option f14">
                        <div class="wj-txt">
                            <textarea name="row[<?php echo $val['id']; ?>]"></textarea>
                        </div>
                        <div class="mes">
                            <span></span>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>
                <?php endforeach; endif; else: echo "" ;endif; ?>
                <!--           <div class="wj-promble-list">
                                <div class="wj-pro-title f16">
                                  1.您的公司简称＋姓名*  
                                </div>
                               <div class="wj-option f14">
                                   <div class="wj-rad">
                                     <input type="radio" value="" name="radio"> <span >单选</span>
                                   </div>
                                   <div class="wj-rad">
                                     <input type="radio" value="" name="radio"> <span>单选</span>
                                   </div>
                                   <div class="wj-rad">
                                       <input type="radio" value="" name="radio"> <span>单选</span>
                                   </div>
                               </div>
                           </div>-->

                <!--           <div class="wj-promble-list">
                                <div class="wj-pro-title f16">
                                  1.您的公司简称＋姓名*  
                                </div>
                               <div class="wj-option f14">
                                   <div class="wj-ck">
                                       <input type="checkbox" name="vehicle" value="Car" checked="checked" /> <span>复选框</span>
                                   </div>
                                    <div class="wj-ck">
                                       <input type="checkbox" name="vehicle" value="Car" checked="checked" /> <span>复选框</span>
                                   </div>
                                    <div class="wj-ck">
                                       <input type="checkbox" name="vehicle" value="Car" checked="checked" /> <span>复选框</span>
                                   </div>
                               </div>
                           </div>-->

                <div class="wj-promble-list">
                    <button type="submit" class="btn btn-info f14">提交</button>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="anIser">
            <div> <image src="/assets/addons/ykquest/image/dui.png"></div>
            <div> 您已经参与过该问卷，谢谢合作 ， <a href="<?php echo addon_url('ykquest/index/index'); ?>"> 返回问卷列表</a></div>
        </div>
        <?php endif; ?>
    </form>
</div>
<script>
    function check() {
        var a = $('form').ghostsf_serialize();
        var arr;
        for (var i = 0; i < a.length; i++) {
            var name = a[i].name;
            var type = $('input[name="' + name + '"]').attr('type');
            if (type == "checkbox" || type == "radio") {
                var b = $('input[name="' + name + '"]').is(':checked');
                if (b == false) {
                    $('input[name="' + name + '"]').parent().siblings('.mes').find("span").html("*请选中一个选项");
                } else {
                    $('input[name="' + name + '"]').parent().siblings('.mes').find("span").html("");
                }
            } else {
                var val = $('textarea[name="' + name + '"]').val();
                if (val == "") {
                    $('textarea[name="' + name + '"]').parent().siblings('.mes').find("span").html("*请填写内容");
                } else {
                    $('textarea[name="' + name + '"]').parent().siblings('.mes').find("span").html("");
                }
            }
        }
        if ($(".mes span").text().length == 0) {
            return true;
        }
        return false;
    }
    function isNull(a) {
        var b = $(a).attr('name');
//       console.log(b)
    }
    $.fn.ghostsf_serialize = function () {
        var a = this.serializeArray();
//        console.log(a)
        var $radio = $('input[type=radio],input[type=checkbox]', this);
        var temp = {};

        $.each($radio, function () {
            if (!temp.hasOwnProperty(this.name)) {
                if ($("input[name='" + this.name + "']:checked").length == 0) {
                    temp[this.name] = "";
                    a.push({name: this.name, value: ""});
                }
            }
        });
        //console.log(a);
        return a;
    };
</script>
<div class="footer">Copyright © 2017-2020</div>
</body>
</html>
