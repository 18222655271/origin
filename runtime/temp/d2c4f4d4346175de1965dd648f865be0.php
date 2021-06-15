<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:57:"D:\phpstudy_pro\WWW\faas\addons\wwh\view\index\index.html";i:1611641096;s:58:"D:\phpstudy_pro\WWW\faas\addons\wwh\view\index\header.html";i:1620800281;s:58:"D:\phpstudy_pro\WWW\faas\addons\wwh\view\index\footer.html";i:1611641096;}*/ ?>
﻿<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no" />
  <meta name="renderer" content="webkit">
  <meta name="keywords" content="<?php echo $config['keywords']; ?>"/>
  <meta name="description" content="<?php echo $config['description']; ?>"/>
  <title>首页_<?php echo $config['site_name']; ?></title>
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
<div id="index">
  <!-- Banner -->
  <div class="in1">
    <div class=" swiper swiper_in1 stop-swiping">
      <div class="swiper-wrapper">
        <?php if(is_array($banner) || $banner instanceof \think\Collection || $banner instanceof \think\Paginator): $k = 0; $__LIST__ = $banner;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
        <div class="swiper-slide">
          <div class="sbox ">
            <a href="<?php echo $vo['url']; ?>"  class="pimg"><img src="<?php echo $vo['pc_image']; ?>" alt="<?php echo $vo['title']; ?>"></a>
            <a href="<?php echo $vo['url']; ?>"  class="mimg"><img src="<?php echo $vo['phone_image']; ?>" alt="<?php echo $vo['title']; ?>" ></a>
            <?php if($k == 1): ?><video src="<?php echo $vo['video_image']; ?>" autoplay="" loop="" muted=""></video><?php endif; ?>
            <div class="txt alltime">
              <div class="tbox">
                <div class="en"><?php echo $vo['bigfont']; ?><span> <?php echo $vo['font']; ?></span></div>
                <div class="tt fbd "><?php echo $vo['title']; ?></div>
                <div class="more">
                  <a href="<?php echo $vo['url']; ?>"><img src="/assets/addons/wwh/images/20200911153229.png" alt="<?php echo $vo['title']; ?>" ></a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; endif; else: echo "" ;endif; ?>
      </div>
      <div class="swiper-pagination pagination alltime"></div>
      <div class="swiper-button-prev prev"></div>
      <div class="swiper-button-next next"></div>
    </div>
  </div>

  <div class="in2">
    <div class="container">
    <div class="last-product">
      <div class="index-title">最新产品</div>
      <div class="items">
        <?php if(is_array($product) || $product instanceof \think\Collection || $product instanceof \think\Paginator): $k = 0; $__LIST__ = $product;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
        <a class="<?php if($k == 1): ?>item item01<?php elseif($k == 6): ?>item item02<?php else: ?>item<?php endif; ?>" <?php if($k == 6): ?>style="margin: 0 0 1.33%"<?php endif; ?> href="<?php echo addon_url('wwh/index/product_detail',[':id'=>$vo['id']]); ?>">
        <div class="pic">
        <img src="<?php echo $vo['index_image']; ?>" alt="<?php echo $vo['productname']; ?>">
        </div>
        <div class="info">
        <p class="t1 over-line1"><?php echo $vo['productname']; ?></p>
        <p class="t2 over-line2"><?php echo $vo['model']; ?></p>
        </div>
        </a>
        <?php endforeach; endif; else: echo "" ;endif; ?>
      </div>
    </div>
  </div>
  </div>

  <div class="in3">
    <div class="about-box">
      <div class="info-box">
        <div class="title-box">
          <div class="title"><?php echo $home['about_title']; ?></div>
          <div class="desc"><?php echo $home['introduction']; ?></div>
        </div>
        <div class="num-box">
          <div class="item">
            <p class="num"> <span><?php echo $home['title1']; ?></span>年</p>
            <div class="tt">
              <?php echo $home['description1']; ?>
            </div>
          </div>
          <div class="item">
            <p class="num"> <span><?php echo $home['title2']; ?></span>强</p>
            <div class="tt">
              <?php echo $home['description2']; ?>
            </div>
          </div>
          <div class="item">
            <p class="num"> <span><?php echo $home['title3']; ?></span>个</p>
            <div class="tt">
              <?php echo $home['description3']; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="in4">
  <div class="wel-news container">
    <div class="wel-news-title">
      新闻中心
      <a href="<?php echo addon_url('wwh/index/news',[':id'=>'']); ?>">查看更多<span></span></a>
    </div>
    <div class="wel-news-list">
      <div class="container">
        <div class="row f-cb">
          <?php if(is_array($news) || $news instanceof \think\Collection || $news instanceof \think\Paginator): $i = 0; $__LIST__ = $news;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 4 );++$i;if($mod == '0'): ?>
          <div class="wt25 news01 wnews">
            <a href="<?php echo addon_url('wwh/index/news_detail',[':id'=>$vo['id']]); ?>" style="background:url(/assets/addons/wwh/images/20200911153308.jpg ) no-repeat center;">
              <h3><?php echo $vo['time']; ?></h3>
              <h2><?php echo $vo['newsname']; ?></h2>
            </a>
          </div>
          <?php endif; if($mod == '1'): ?>
          <div class="wt50 news02 wnews">
            <a href="<?php echo addon_url('wwh/index/news_detail',[':id'=>$vo['id']]); ?>" style="background:url(/assets/addons/wwh/images/20200911153343.jpg ) no-repeat center;">
              <h3><?php echo $vo['time']; ?></h3>
              <h2><?php echo $vo['newsname']; ?></h2>
              <br>
              <br>
              <p class="content"><?php echo mb_substr(strip_tags($vo['content']),0,30,'utf-8'); ?>...</p>
              <p class="more">查看详情 +</p>
            </a>
          </div>
          <?php endif; if($mod == '2'): ?>
          <div class="wt25 news03 wnews">
            <a href="<?php echo addon_url('wwh/index/news_detail',[':id'=>$vo['id']]); ?>" style="background:#0f6ab4;">
              <h3><?php echo $vo['time']; ?></h3>
              <h2><?php echo $vo['newsname']; ?></h2>
            </a>
            <?php endif; if($mod == '3'): ?>
            <a href="<?php echo addon_url('wwh/index/news_detail',[':id'=>$vo['id']]); ?>" style="background:url(/assets/addons/wwh/images/20200911153417.jpg ) no-repeat center;">
              <h3><?php echo $vo['time']; ?></h3>
              <h2><?php echo $vo['newsname']; ?></h2>
            </a>
          </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
        </div>
      </div>
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
<script src="/assets/addons/wwh/js/wow.js"></script>
<script src="/assets/addons/wwh/js/style.js"></script>
<script src="/assets/addons/wwh/js/jquery.countup.min.js"></script>
<script src="/assets/addons/wwh/js/jquery.waypoints.min.js"></script>
<script src="/assets/addons/wwh/js/swiper.min.js"></script>
<script type="text/javascript">
    $('.header-container .clearfix .H-nav .Hnav-menu').eq(0).addClass('nav-active');//顶部导航变色

    /*数字滚动*/
    new WOW().init();
    $('.num-box .item .num span ').countUp({
        delay: 10,
        time: 500
    });

    /*首页轮播*/
    var swiper = new Swiper('.swiper', {
        autoplay: 4000,
        speed:1000,
        effect : 'fade',
        autoplayDisableOnInteraction : false,
        slidesPerView : 1,
        spaceBetween: 0,
        noSwiping : true,
        noSwipingClass : 'stop-swiping',
        prevButton:'.prev',
        nextButton:'.next',
        pagination: '.pagination',
        centeredSlides : true,
        paginationClickable: true,
        breakpoints: {
            //当宽度小于等于960
            960: {
                noSwiping : false,
                effect : 'fade',
            }
        },
    })

    $(".i1 .swiper_in1").hover(function(){
        swiper.stopAutoplay();
    },function(){
        swiper.startAutoplay();
    });
</script>
</body>
</html>
