<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:59:"D:\phpstudy_pro\WWW\faas\addons\wwh\view\index\product.html";i:1611641096;s:58:"D:\phpstudy_pro\WWW\faas\addons\wwh\view\index\header.html";i:1620800281;s:58:"D:\phpstudy_pro\WWW\faas\addons\wwh\view\index\footer.html";i:1611641096;}*/ ?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no" />
    <meta name="renderer" content="webkit">
    <meta name="keywords" content=" "/>
    <meta name="description" content=" "/>
    <title>产品中心_<?php echo $config['site_name']; ?></title>
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
<div id="bann" style="background-image: url(<?php echo $config['banner1']; ?>);">
    <div class="container">
        <div class="t1">
            PRODUCT CENTER<br>
        </div>
        <div class="t2" id="pro">
            <span>强大的产品研发能力和创新能力</span>
        </div>
    </div>
</div>

<div id="nav2">
    <div class="sub-menu ">
        <div class="main container">
            <div class="sub-nav">
                <div class="items">
                    <?php if(is_array($productcategory) || $productcategory instanceof \think\Collection || $productcategory instanceof \think\Paginator): if( count($productcategory)==0 ) : echo "" ;else: foreach($productcategory as $key=>$vo): ?>
                    <a <?php if($vo['id'] == $id): ?> class="active" <?php endif; ?> href="<?php echo addon_url('wwh/index/product', [':id'=>$vo['id']]); ?>"><?php echo $vo['name']; ?></a><i></i>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>
            <div class="hamb">当前位置：产品中心</div>
        </div>
    </div>
</div>

<?php if($prostatus == '1'): ?>
<div class="pn-box">
    <div class="pn-title">
        <span>产</span>
        <span>品</span>
        <span>分</span>
        <span>类</span>
    </div>
    <div class="pn-list">
        <ul class="f-cb">
            <?php if(is_array($name) || $name instanceof \think\Collection || $name instanceof \think\Paginator): $i = 0; $__LIST__ = $name;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <li>
                <div class="pn-tit-one pn-tit" href="javascript:;"><a href="<?php echo addon_url('wwh/index/product', [':id'=>$vo['id']]); ?>" ><?php echo $vo['name']; ?></a></div>
                <div class="pn-con-one pn-content">
                    <?php if(is_array($vo['child']) || $vo['child'] instanceof \think\Collection || $vo['child'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?>
                    <div class="pn-tit-two pn-tit" href="javascript:;"><a href="<?php echo addon_url('wwh/index/product', [':id'=>$vo2['id']]); ?>"><?php echo $vo2['name']; ?></a></div>
                    <div class="pn-con-two pn-content">
                        <?php if(is_array($vo2['child']) || $vo2['child'] instanceof \think\Collection || $vo2['child'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo2['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo3): $mod = ($i % 2 );++$i;?>
                        <a <?php if($vo3['id'] == $id): ?>class="pn-tit-three cut cursleect font-blod pn-tit" <?php else: ?> class="pn-tit-three  pn-tit" <?php endif; ?> href="<?php echo addon_url('wwh/index/product', [':id'=>$vo3['id']]); ?>">&nbsp;&nbsp;<?php echo $vo3['name']; ?></a>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </li>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div>
</div>
<?php else: endif; ?>

<div id="product">
    <div class="container">
        <div class="pro-list" id="shuju">
            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <a class="item" href="<?php echo addon_url('wwh/index/product_detail',[':id'=>$vo['id']]); ?>">
                <div class="pic">
                    <img src="<?php echo $vo['indent_image']; ?>" alt="<?php echo $vo['productname']; ?>">
                </div>
                <div class="info">
                    <p class="t1 over-line1"><?php echo $vo['productname']; ?></p>
                    <p class="t2 over-line2"><?php echo $vo['model']; ?></p>
                </div>
            </a>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        <div class="fenye"><?php echo $page; ?></div>
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
<script src="/assets/addons/wwh/js/style.js"></script>
<script type="text/javascript">
    $('.header-container .clearfix .H-nav .Hnav-menu').eq(1).addClass('nav-active');//顶部导航变色

    $(document).on('click','.pagination a',function(event){
        event.preventDefault();
        _this = $(this);
        var href = _this.attr("href");
        $.ajax({
            url:href,
            dataType:'json',
            type:"post",
            data:[],
            success:function(res){
                console.log(res);
                $("#shuju").empty();
                var str = "";
                $.each(res.list.data,function(k,v){
                    str+='<a class="item" href="<?php echo addon_url('wwh/index/product_detail',[':id'=>"'+v.id+'"]); ?>">\
                    <div class="pic">\
                        <img src="'+v.indent_image+'" alt="'+v.productname+'">\
                        </div>\
                        <div class="info">\
                        <p class="t1 over-line1">'+v.productname+'</p>\
                        <p class="t2 over-line2">'+v.model+'</p>\
                        </div>\
                        </a>';
                });
                location.href = "#pro";
                $('#shuju').html(str);
                $(".fenye").html(res.page);
            }
        })
    });

    $(function(){

        $('.pn-list .pn-tit.cursleect').parents('.pn-content').slideDown().prev('.pn-tit').addClass('cur cut');

        var retractLeft = $(".pn-list").width();
        $('.pn-box').hover(function(){
            $('.pn-list').addClass('on')
            $('.pn-retract').css("left",retractLeft)
        },function(){
            $('.pn-list').removeClass('on')
            $('.pn-retract').css("left",-retractLeft)
        })

        $('.pn-retract').hover(function() {
            $('.pn-list').removeClass('on');
            $('.pn-retract').css("left",-retractLeft)
        },function(){
            $('.pn-list').addClass('on')
            $('.pn-retract').css("left",retractLeft)
        })

        $('.pn-list li .pn-tit-one').on('click',function(){
            var sss = $(this).parent('li').index();
            var len = $('.pn-list li').length-1;
            if (sss == len) {
                var off = $(this).offset().top - ($('.pn-list').height()/2);
                $('.pn-list').stop().animate({'scrollTop':off},500);
            };

            if (!$(this).hasClass('cut')) {
                $(this).addClass('cut').parents('li').siblings('li').find('.pn-tit-one').removeClass('cut');
                $(this).next('.pn-con-one').slideDown().parents('li').siblings('li').find('.pn-con-one').slideUp()
            }else{
                $(this).removeClass('cut')
                $(this).next('.pn-con-one').slideUp()
            }
        })
        $('.pn-list li .pn-tit-two').on('click',function(){
            var sss = $(this).parent('li').index();
            var len = $('.pn-list li').length-1;
            if (sss == len) {
                var off = $(this).offset().top - ($('.pn-list').height()/2);
                $('.pn-list').stop().animate({'scrollTop':off},500);
            };

            if (!$(this).hasClass('cut')) {
                $(this).addClass('cut').parents('li').siblings('li').find('.pn-tit-two').removeClass('cut');
                $(this).next('.pn-con-two').slideDown().parents('li').siblings('li').find('.pn-con-two').slideUp()
            }else{
                $(this).removeClass('cut')
                $(this).next('.pn-con-two').slideUp()
            }
        })

        $('.pn-list li .pn-tit-three').on('click',function(){
            if (!$(this).hasClass('cur')) {
                $(this).addClass('cur').siblings('.pn-tit-three').removeClass('cur').parents('li').siblings('li').find('.pn-tit-three').removeClass('cur');
                $(this).next('.pn-con-three').slideDown().siblings('.pn-con-three').slideUp().parents('li').siblings('li').find('.pn-con-three').slideUp()
            }else{
                $(this).removeClass('cur')
                $(this).next('.pn-con-three').slideUp()
            }
        })

    })
</script>
</body>
</html>
