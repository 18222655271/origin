<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:77:"D:\phpstudy_pro\WWW\faas\public/../application/admin\view\wwh\home\index.html";i:1623313646;s:67:"D:\phpstudy_pro\WWW\faas\application\admin\view\layout\default.html";i:1617358420;s:64:"D:\phpstudy_pro\WWW\faas\application\admin\view\common\meta.html";i:1617358420;s:66:"D:\phpstudy_pro\WWW\faas\application\admin\view\common\script.html";i:1617358420;}*/ ?>
<!DOCTYPE html>
<html lang="<?php echo $config['language']; ?>">
    <head>
        <meta charset="utf-8">
<title><?php echo (isset($title) && ($title !== '')?$title:''); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">
<meta name="referrer" content="never">
<meta name="robots" content="noindex, nofollow">

<link rel="shortcut icon" href="/assets/img/favicon.ico" />
<!-- Loading Bootstrap -->
<link href="/assets/css/backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">

<?php if(\think\Config::get('fastadmin.adminskin')): ?>
<link href="/assets/css/skins/<?php echo \think\Config::get('fastadmin.adminskin'); ?>.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">
<?php endif; ?>

<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
  <script src="/assets/js/html5shiv.js"></script>
  <script src="/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
    var require = {
        config:  <?php echo json_encode($config); ?>
    };
</script>

    </head>

    <body class="inside-header inside-aside <?php echo defined('IS_DIALOG') && IS_DIALOG ? 'is-dialog' : ''; ?>">
        <div id="main" role="main">
            <div class="tab-content tab-addtabs">
                <div id="content">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <section class="content-header hide">
                                <h1>
                                    <?php echo __('Dashboard'); ?>
                                    <small><?php echo __('Control panel'); ?></small>
                                </h1>
                            </section>
                            <?php if(!IS_DIALOG && !\think\Config::get('fastadmin.multiplenav') && \think\Config::get('fastadmin.breadcrumb')): ?>
                            <!-- RIBBON -->
                            <div id="ribbon">
                                <ol class="breadcrumb pull-left">
                                    <?php if($auth->check('dashboard')): ?>
                                    <li><a href="dashboard" class="addtabsit"><i class="fa fa-dashboard"></i> <?php echo __('Dashboard'); ?></a></li>
                                    <?php endif; ?>
                                </ol>
                                <ol class="breadcrumb pull-right">
                                    <?php foreach($breadcrumb as $vo): ?>
                                    <li><a href="javascript:;" data-url="<?php echo $vo['url']; ?>"><?php echo $vo['title']; ?></a></li>
                                    <?php endforeach; ?>
                                </ol>
                            </div>
                            <!-- END RIBBON -->
                            <?php endif; ?>
                            <div class="content">
                                <html>
 <head>
  <style type="text/css">
    @media (max-width: 375px) {
        .edit-form tr td input {
            width: 100%;
        }

        .edit-form tr th:first-child, .edit-form tr td:first-child {
            width: 20%;
        }

        .edit-form tr th:nth-last-of-type(-n+2), .edit-form tr td:nth-last-of-type(-n+2) {
            display: none;
        }
    }

    .edit-form table > tbody > tr td a.btn-delcfg {
        visibility: hidden;
    }

    .edit-form table > tbody > tr:hover td a.btn-delcfg {
        visibility: visible;
    }
</style> 
 </head>
 <body>
  <div class="panel panel-default panel-intro"> 
   <div class="panel-heading">  
    <ul class="nav nav-tabs"> 
     <li class="active"><a href="#enterprise" data-toggle="tab">关于我们</a></li>	 
    </ul> 
   </div> 
   <div class="panel-body"> 
    <div id="myTabContent" class="tab-content"> 
     <div class="tab-pane fade active in" id="enterprise"> 
      <div class="widget-body no-padding"> 
       <form id="basic-form" class="edit-form form-horizontal nice-validator n-default n-bootstrap" role="form" data-toggle="validator" method="POST" action="<?php echo url('Admin/wwh/Home/enterprise'); ?>" novalidate="novalidate"> 
        <input type="hidden" name="__token__" value="3d21ee81f41717393f2f12f92c2fa29f" /> 
        <table class="table table-striped"> 
         <tbody> 
          <tr> 
           <td style="width:8%">关于标题</td> 
           <td> 
            <div class="row"> 
             <div class="col-sm-8 col-xs-12"> 
              <input type="text" name="about_title" value="<?php echo $data['about_title']; ?>" class="form-control" /> 
             </div> 
             <div class="col-sm-4"></div> 
            </div> </td> 
           <td>about_title</td> 
           <td></td> 
          </tr> 
          <tr> 
           <td style="width:8%">关于介绍</td> 
           <td> 
            <div class="row"> 
             <div class="col-sm-8 col-xs-12"> 
			  <textarea id="c-introduction" name="introduction" class="form-control editor" rows="5" name="introduction" cols="50"><?php echo htmlentities($data['introduction']); ?></textarea>
             </div> 
             <div class="col-sm-4"></div> 
            </div> </td> 
           <td>introduction</td> 
           <td></td> 
          </tr> 
          <tr> 
           <td style="width:8%">标题1</td> 
           <td> 
            <div class="row"> 
             <div class="col-sm-8 col-xs-12"> 
              <input type="text" name="title1" value="<?php echo $data['title1']; ?>" class="form-control" /> 
             </div> 
             <div class="col-sm-4"></div> 
            </div> </td> 
           <td>title1</td> 
           <td></td> 
          </tr>
          <tr> 
           <td style="width:8%">描述1</td> 
           <td> 
            <div class="row"> 
             <div class="col-sm-8 col-xs-12"> 
              <input type="text" name="description1" value="<?php echo $data['description1']; ?>" class="form-control" />
             </div> 
             <div class="col-sm-4"></div> 
            </div> </td> 
           <td>description1</td> 
           <td></td> 
          </tr>
          <tr> 
           <td style="width:8%">标题2</td> 
           <td> 
            <div class="row"> 
             <div class="col-sm-8 col-xs-12"> 
              <input type="text" name="title2" value="<?php echo $data['title2']; ?>" class="form-control" />
             </div> 
             <div class="col-sm-4"></div> 
            </div> </td> 
           <td>title2</td> 
           <td></td> 
          </tr>
          <tr> 
           <td style="width:8%">描述2</td> 
           <td> 
            <div class="row"> 
             <div class="col-sm-8 col-xs-12"> 
              <input type="text" name="description2" value="<?php echo $data['description2']; ?>" class="form-control" />
             </div> 
             <div class="col-sm-4"></div> 
            </div> </td> 
           <td>description2</td> 
           <td></td> 
          </tr>	
          <tr> 
           <td style="width:8%">标题3</td> 
           <td> 
            <div class="row"> 
             <div class="col-sm-8 col-xs-12"> 
              <input type="text" name="title3" value="<?php echo $data['title3']; ?>" class="form-control" />
             </div> 
             <div class="col-sm-4"></div> 
            </div> </td> 
           <td>title3</td> 
           <td></td> 
          </tr>
          <tr> 
           <td style="width:8%">描述3</td> 
           <td> 
            <div class="row"> 
             <div class="col-sm-8 col-xs-12"> 
              <input type="text" name="description3" value="<?php echo $data['description3']; ?>" class="form-control" />
             </div> 
             <div class="col-sm-4"></div> 
            </div> </td> 
           <td>description3</td> 
           <td></td> 
          </tr>		  
         </tbody> 
         <tfoot> 
          <tr> 
           <td></td> 
           <td> <button type="submit" class="btn btn-success btn-embossed">保存</button> </td> 
           <td></td> 
           <td></td> 
          </tr> 
         </tfoot> 
        </table> 
       </form> 
      </div> 
     </div>

    </div> 
   </div> 
  </div>
 </body>
</html>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo htmlentities($site['version']); ?>"></script>
    </body>
</html>
