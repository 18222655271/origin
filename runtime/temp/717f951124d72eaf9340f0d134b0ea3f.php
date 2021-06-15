<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:59:"D:\phpstudy_pro\WWW\faas\addons\wwh\view\index\contact.html";i:1620800309;s:58:"D:\phpstudy_pro\WWW\faas\addons\wwh\view\index\header.html";i:1620800281;s:58:"D:\phpstudy_pro\WWW\faas\addons\wwh\view\index\footer.html";i:1611641096;}*/ ?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no" />
    <meta name="renderer" content="webkit">
    <meta name="keywords" content=" "/>
    <meta name="description" content=" "/>
    <title>联系我们_<?php echo $config['site_name']; ?></title>
    <link rel="stylesheet" href="/assets/addons/wwh/css/swiper.css">
    <link rel="stylesheet" href="/assets/addons/wwh/css/style.css">
    <link rel="stylesheet" href="/assets/addons/wwh/css/media.css">
    <link rel="shortcut icon" href="/assets/addons/wwh/images/favicon.ico">
</head>
<body>
<!--头部-->
<div class="Header-wrapper">
  <div class="header-container">
    <div class="contain clearfix">
      <a href="<?php echo addon_url('wwh/index/index'); ?>" title="" class="H-logo">
        <img src="<?php echo $config['logo']; ?>" alt="" class="" />
      </a>
      <div class="Hmenu-btn H-rMenu-btn">
        <a></a>
      </div>
      <div class="Hmenu-btn Hmenu-web">
        <a></a>
      </div>
      <ul class="H-nav clearfix">
        <li>
          <a href="<?php echo addon_url('wwh/index/index'); ?>" title="" class="Hnav-menu">首页
            <i></i>
          </a>
        </li>
        <li>
          <a href="<?php echo addon_url('wwh/index/product',[':id'=>'']); ?>" title="" class="Hnav-menu">产品中心
            <i></i>
          </a>
          <div class="Hnav-sub">
            <div class="contain clearfix">
              <ul class="HnavSub-list clearfix">
                <?php if(is_array($productcategory) || $productcategory instanceof \think\Collection || $productcategory instanceof \think\Paginator): $i = 0; $__LIST__ = $productcategory;if( count($__LIST__)==0 ) : echo "请导入测试数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <li>
                  <a href="<?php echo addon_url('wwh/index/product', [':id'=>$vo['id']]); ?>" title=""><?php echo $vo['name']; ?>
                    <i></i>
                  </a>
                </li>
                <?php endforeach; endif; else: echo "请导入测试数据" ;endif; ?>
              </ul>
              <div class="HnavSub-box">
                <div class="HnavSub-box-pic">
                  <a href="<?php echo addon_url('wwh/index/product',[':id'=>'']); ?>">
                    <img src="/assets/addons/wwh/images/20200911152527.jpg" alt="">
                  </a>
                </div>
              </div>
            </div>
          </div>
        <li>
          <a href="<?php echo addon_url('wwh/index/cases',[':id'=>'']); ?>" title="" class="Hnav-menu">解决方案
            <i></i>
          </a>
          <div class="Hnav-sub">
            <div class="contain clearfix">
              <ul class="HnavSub-list clearfix">
                <?php if(is_array($casescategory) || $casescategory instanceof \think\Collection || $casescategory instanceof \think\Paginator): $i = 0; $__LIST__ = $casescategory;if( count($__LIST__)==0 ) : echo "请导入测试数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <li>
                  <a href="<?php echo addon_url('wwh/index/cases', [':id'=>$vo['id']]); ?>" title=""><?php echo $vo['name']; ?>
                    <i></i>
                  </a>
                </li>
                <?php endforeach; endif; else: echo "请导入测试数据" ;endif; ?>
              </ul>
              <div class="HnavSub-box">
                <div class="HnavSub-box-pic">
                  <a href="<?php echo addon_url('wwh/index/cases',[':id'=>'']); ?>">
                    <img src="/assets/addons/wwh/images/20200911152601.jpg" alt="">
                  </a>
                </div>
              </div>
            </div>
          </div>
        <li>
        <a href="<?php echo addon_url('wwh/index/service'); ?>" title="" class="Hnav-menu">服务中心
          <i></i>
        </a>
        <div class="Hnav-sub">
          <div class="contain clearfix">
            <ul class="HnavSub-list clearfix">
              <li>
                <a href="<?php echo addon_url('wwh/index/service'); ?>" title="">服务策略
                  <i></i>
                </a>
              </li><li>
              <a href="<?php echo addon_url('wwh/index/market'); ?>" title="">营销网络
                <i></i>
              </a>
            </li><li>
              <a href="<?php echo addon_url('wwh/index/download'); ?>" title="">资料下载
                <i></i>
              </a>
            </li>
            </ul>
            <div class="HnavSub-box">
              <div class="HnavSub-box-pic">
                <a href="<?php echo addon_url('wwh/index/service'); ?>">
                  <img src="/assets/addons/wwh/images/20200911152638.jpg" alt="">
                </a>
              </div>
            </div>
          </div>
        </div><li>
        <a href="<?php echo addon_url('wwh/index/news',[':id'=>'']); ?>" title="" class="Hnav-menu">新闻中心
          <i></i>
        </a>
        <div class="Hnav-sub">
          <div class="contain clearfix">
            <ul class="HnavSub-list clearfix">
              <?php if(is_array($newscategory) || $newscategory instanceof \think\Collection || $newscategory instanceof \think\Paginator): $i = 0; $__LIST__ = $newscategory;if( count($__LIST__)==0 ) : echo "请导入测试数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
              <li>
                <a href="<?php echo addon_url('wwh/index/news', [':id'=>$vo['id']]); ?>" title=""><?php echo $vo['name']; ?>
                  <i></i>
                </a>
              </li>
              <?php endforeach; endif; else: echo "请导入测试数据" ;endif; ?>
            </ul>
            <div class="HnavSub-box">
              <div class="HnavSub-box-pic">
                <a href="<?php echo addon_url('wwh/index/news',[':id'=>'']); ?>">
                  <img src="/assets/addons/wwh/images/20200911152718.jpg" alt="">
                </a>
              </div>
            </div>
          </div>
        </div><li>
        <a href="<?php echo addon_url('wwh/index/about'); ?>" title="" class="Hnav-menu">关于我们
          <i></i>
        </a>
        <div class="Hnav-sub">
          <div class="contain clearfix">
            <ul class="HnavSub-list clearfix">
              <li>
                <a href="<?php echo addon_url('wwh/index/about'); ?>" title="">公司概况
                  <i></i>
                </a>
              </li><li>
              <a href="<?php echo addon_url('wwh/index/honor'); ?>" title="">荣誉资质
                <i></i>
              </a>
            </li><li>
              <a href="<?php echo addon_url('wwh/index/join'); ?>" title="">加入我们
                <i></i>
              </a>
            </li><li>
              <a href="<?php echo addon_url('wwh/index/contact'); ?>" title="">联系我们
                <i></i>
              </a>
            </li>
            </ul>
            <div class="HnavSub-box">
              <div class="HnavSub-box-pic">
                <a href="<?php echo addon_url('wwh/index/about'); ?>">
                  <img src="/assets/addons/wwh/images/20200911152752.jpg" alt="">
                </a>
              </div>
            </div>
          </div>
        </div>
      </ul>
      <div class="H-action">
        <div class="H-mail">
          <a href="mailto:<?php echo $config['email']; ?>" title="" class="Hmail-menu">
            <i></i>
            <span>Mail</span>
            <b></b>
          </a>
        </div>
        <div class="H-srch">
          <a href="javascript:;" title="" class="Hsrch-menu">
            <span>Search</span>
            <i></i>
          </a>
          <div class="Hsrch-box">
            <div class="Hsrch-block contain clearfix">
              <form action="<?php echo addon_url('wwh/index/search'); ?>" method="post" id="top_form">
                <input type="submit" value="" class="btn" id="top_btn" />
                <input type="text" placeholder="新闻搜索..." class="text" name="keywords" id="top_key" />
                <a href="javascript:;" title="" class="close"></a>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
    // 搜索
    $("#top_btn").click(function () {
        var top_key = $.trim($("#top_key").val());
        if (top_key == '') {
            alert("Please enter keywords");
            return false;
        }
        $("#top_form").submit();
    });
</script>

<!--内容-->
<div id="bann" style="background-image: url(<?php echo $config['banner5']; ?>);">
    <div class="container">
        <div class="t1">
            ABOUT US<br>
        </div>
        <div class="t2">
            <span>专注于计算机通信集成（CTI）领域</span>
        </div>
    </div>
</div>

<div id="nav2">
    <div class="sub-menu ">
        <div class="main container">
            <div class="sub-nav">
                <div class="items">
                    <a href="<?php echo addon_url('wwh/index/about'); ?>">公司概况</a><i></i>
                    <a href="<?php echo addon_url('wwh/index/honor'); ?>">荣誉资质</a><i></i>
                    <a href="<?php echo addon_url('wwh/index/join'); ?>">加入我们</a><i></i>
                    <a class="active" href="<?php echo addon_url('wwh/index/contact'); ?>">联系我们</a><i></i>
                </div>
            </div>
            <div class="hamb">当前位置：关于我们</div>
        </div>
    </div>
</div>

<div id="contact">
    <div class="container">
        <div class="contact-box">
            <div class="address-items">
                <div class="item">
                    <div class="img">
                    <img src="/assets/addons/wwh/images/tel1.png" alt="">
                    <img src="/assets/addons/wwh/images/tel2.png" alt="">
                    </div>
                    <p class="t1">总机</p>
                    <p class="desc"><?php echo $data['tel']; ?></p>
                </div>
                <div class="item">
                    <div class="img">
                        <img src="/assets/addons/wwh/images/fax1.png" alt="">
                        <img src="/assets/addons/wwh/images/fax2.png" alt="">
                    </div>
                    <p class="t1">传真</p>
                    <p class="desc"><?php echo $data['fax']; ?></p>
                </div>
                <div class="item">
                    <div class="img">
                        <img src="/assets/addons/wwh/images/mail1.png" alt="">
                        <img src="/assets/addons/wwh/images/mail2.png" alt="">
                    </div>
                    <p class="t1">邮箱</p>
                    <p class="desc"><?php echo $data['email']; ?></p>
                </div>
                <div class="item">
                    <div class="img">
                        <img src="/assets/addons/wwh/images/time1.png" alt="">
                        <img src="/assets/addons/wwh/images/time2.png" alt="">
                    </div>
                    <p class="t1">工作时间</p>
                    <p class="desc"><?php echo $data['time']; ?></p>
                </div>
                <div class="item">
                    <div class="img">
                        <img src="/assets/addons/wwh/images/add1.png" alt="">
                        <img src="/assets/addons/wwh/images/add2.png" alt="">
                    </div>
                    <p class="t1">地址</p>
                    <p class="desc"><?php echo $data['address']; ?></p>
                </div>
            </div>

            <div class="map-box">
                <div id="map"></div>
            </div>

        <div class="message-box" id="message">
            <div class="title"><span>客户留言</span>欢迎给我们留言</div>
            <form id="ct_btn" method="post" action="" class="form-box">
                <div class="line has-star">
                    <input type="text" placeholder="姓名" name="realname" id="realname">
                </div>
                <div class="line has-star">
                    <input type="text" placeholder="公司名称" name="company" id="company">
                </div>
                <div class="line has-star">
                    <input type="text" placeholder="电话" name="tel" id="tel">
                </div>
                <div class="line">
                    <input type="text" placeholder="邮箱" name="email" id="email">
                </div>
                <div class="line has-text has-star">
                    <textarea name="content" id="content" cols="30" rows="10" placeholder="留言内容"></textarea>
                </div>
                <div class="code-box">
                    <div class="line has-star">
                        <input type="text" placeholder="验证码" name="verify" id="verify">
                    </div>
                    <div class="img-box">
                        <img id="verifyImgs" src="<?php echo addon_url('wwh/index/verify'); ?>" alt="验证码" title="看不清楚?换一张" onclick="this.src='<?php echo addon_url('wwh/index/verify'); ?>?seed='+Math.random()"/>                    </div>
                </div>
                <button class="msg-btn" type="button" onClick="ct_btn()">留言提交</button>
            </form>
        </div>

        </div>
    </div>
</div>

<!--底部-->
<!-- 返回顶部 -->
<div class="right clearfix">
  <ul class="ul1">
    <li class="clearfix dh">
      <div class="img"><img src="/assets/addons/wwh/images/tel.png"></div>
      <div class="cla"><span>0571-88888888</span></div>
    </li>
    <li class="clearfix ly" >
      <a target="_blank" href="<?php echo addon_url('wwh/index/contact'); ?>#message">
        <div class="img"><img src="/assets/addons/wwh/images/ly.png"></div>
        <div class="cla"><span>客户留言</span></div>
      </a>
    </li>
    <li class="clearfix top">
      <div class="img"><img src="/assets/addons/wwh/images/top.png"></div>
    </li>
  </ul>
</div>

<div class="footer">
  <div class="foot-tops">
    <div class="container">
      <div class="left-nav">
        <?php echo $config['content']; ?>
      </div>
      <div class="right-box">
        <div class="tt">关注我们<span></span></div>
        <div class="code-box">
          <img src="<?php echo $config['image']; ?>" alt="wechat">
        </div>
        <div class="text"><img src="/assets/addons/wwh/images/wx.png" alt=""><span>打开微信扫一扫</span></div>
      </div>
    </div>
  </div>
  <div class="footer_b">
    <h3><?php echo $config['copyright']; ?><i><span><a target="_blank" href="http://www.beian.gov.cn/"><img style="margin:-8px 2px 0 0;"src="/assets/addons/wwh/images/beian.png">&nbsp;<?php echo $config['gongwang']; ?> | <a href="http://www.beian.miit.gov.cn/" target="_blank" rel="noopener noreferrer"><?php echo $config['beian']; ?></a></span></i></h3>
  </div>
</div>
<script src="/assets/addons/wwh/js/jquery.min.js"></script>
<script src="/assets/addons/wwh/js/swiper.min.js"></script>
<script src="/assets/addons/wwh/js/style.js"></script>
<script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=65557d5eb3cf67817f33df489fb96458"></script>
<script>
    $('.header-container .clearfix .H-nav .Hnav-menu').eq(5).addClass('nav-active');//顶部导航变色
    function loadmap(){
        // 百度地图API功能
        var map = new BMap.Map("map");
        var point = new BMap.Point(120.210704,30.20676);
        var myIcon = new BMap.Icon("/assets/addons/wwh/images/zb.png", new BMap.Size(25,25), {
            // 指定定位位置。
            // 当标注显示在地图上时，其所指向的地理位置距离图标左上
            // 角各偏移10像素和25像素。您可以看到在本例中该位置即是
            // 图标中央下端的尖角位置。
            // 设置图片偏移。
            // 当您需要从一幅较大的图片中截取某部分作为标注图标时，您
            // 需要指定大图的偏移位置，此做法与css sprites技术类似。
            imageOffset: new BMap.Size(0, 0)   // 设置图片偏移
        });
        map.enableScrollWheelZoom();   //启用滚轮放大缩小，默认禁用
        map.enableContinuousZoom();    //启用地图惯性拖拽，默认禁用
        map.centerAndZoom(point, 16);   //地图层级
        var marker = new BMap.Marker(point,{icon: myIcon});  // 创建标注
        map.addOverlay(marker);               // 将标注添加到地图中
        var infohtml = '<div class="map-info"><div class="info"><p class="t1">杭州演示站点股份有限公司</p><p class="t2">浙江省杭州市滨江区江汉路1515号</p></div><a class="gps-box" href="http://api.map.baidu.com/marker?location=30.20676,120.210704&title=我的位置&content=杭州演示站点股份有限公司&output=html" target="_blank"><img src="/assets/addons/wwh/images/20200813153230.png"><p  class="tt">到这去</p></div>'
        var label = new BMap.Label(infohtml,{offset:new BMap.Size(-80,-130)});
        marker.setLabel(label);
    }
    loadmap();

    //客户留言
    function ct_btn(){
        var realname=$.trim($("#realname").val());
        var company=$.trim($("#company").val());
        var tel=$.trim($("#tel").val());
        var email=$.trim($("#email").val());
        var content=$.trim($("#content").val());
        var verify=$.trim($("#verify").val());
        if(realname==''){alert('请填写姓名');return false;}
        if(tel==''){alert('请填写电话');return false;}
        var reg=/(^(\d{3,4}-)?\d{7,8})$|(1[3|4|5|7|8|9]\d{9})$/;
        if(!reg.test(tel)){alert('请填写有效的电话');return false;}
//        if(email==''){alert('请填写邮箱');return false;}
//        var myreg =/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
//        if(!myreg.test(email)){alert('请填写有效的邮箱');return false;}
        if(company==''){alert('请填写公司名称');return false;}
        if(content==''){alert('请填写内容');return false;}
        if(verify==''){alert('请填写验证码');return false;}

        //重载验证码
        function fleshVerify(){
            $('#verifyImgs').attr('src','<?php echo addon_url('wwh/index/verify'); ?>?seed='+Math.random());
        }

        $.ajax({
            url: "<?php echo addon_url('wwh/index/message'); ?>",
            type: "POST",
            data: {'realname':realname,'company':company,'tel':tel,'email':email,'content':content,'verify':verify},
            async: true,
            dataType: "json",
            success: function(mes){
                if(mes==1){
                    alert("提交成功");
                    window.location.reload();
                }else if(mes==2){
                    alert("请检查后台邮件配置是否正确");
                    fleshVerify();
                }else {
                    alert("验证码错误，请重试");
                }

            }
        });
    }
</script>
</body>
</html>
