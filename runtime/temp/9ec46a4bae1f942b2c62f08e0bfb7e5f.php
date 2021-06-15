<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:78:"D:\phpstudy_pro\WWW\faas\public/../application/admin\view\wwh\about\index.html";i:1623313646;s:67:"D:\phpstudy_pro\WWW\faas\application\admin\view\layout\default.html";i:1617358420;s:64:"D:\phpstudy_pro\WWW\faas\application\admin\view\common\meta.html";i:1617358420;s:66:"D:\phpstudy_pro\WWW\faas\application\admin\view\common\script.html";i:1617358420;}*/ ?>
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
     <li class="active"><a href="#about" data-toggle="tab">企业介绍</a></li>
     <li class=""><a href="#culture" data-toggle="tab">企业文化</a></li>
    </ul> 
   </div> 
   <div class="panel-body"> 
    <div id="myTabContent" class="tab-content"> 
     <div class="tab-pane fade active in" id="about"> 
      <div class="widget-body no-padding"> 
       <form id="basic-form" class="edit-form form-horizontal nice-validator n-default n-bootstrap" role="form" data-toggle="validator" method="POST" action="<?php echo url('Admin/wwh/About/about'); ?>" novalidate="novalidate"> 
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
           <td style="width:8%">关于简述</td>
           <td>
            <div class="row">
             <div class="col-sm-8 col-xs-12">
              <textarea id="c-about_description" name="about_description" class="form-control editor" rows="5" name="about_description" cols="50"><?php echo htmlentities($data['about_description']); ?></textarea>
             </div>
             <div class="col-sm-4"></div>
            </div> </td>
           <td>about_description</td>
           <td></td>
          </tr> 
          <tr> 
           <td style="width:8%">关于内容</td> 
           <td> 
            <div class="row"> 
             <div class="col-sm-8 col-xs-12"> 
              <textarea id="c-about_content" name="about_content" class="form-control editor" rows="5" name="about_content" cols="50"><?php echo htmlentities($data['about_content']); ?></textarea>
             </div> 
             <div class="col-sm-4"></div> 
            </div> </td> 
           <td>about_content</td> 
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

    <div class="tab-pane fade " id="culture">
      <div class="widget-body no-padding"> 
       <form id="basic-form" class="edit-form form-horizontal nice-validator n-default n-bootstrap" role="form" data-toggle="validator" method="POST" action="<?php echo url('Admin/wwh/About/culture'); ?>" novalidate="novalidate"> 
        <input type="hidden" name="__token__" value="3d21ee81f41717393f2f12f92c2fa29f" /> 
        <table class="table table-striped"> 
         <tbody> 
          <tr>
           <td style="width:8%">企业文化标题1</td> 
           <td> 
            <div class="row"> 
             <div class="col-sm-8 col-xs-12"> 
              <input type="text" name="culture_title1" value="<?php echo $data['culture_title1']; ?>" class="form-control" /> 
             </div> 
             <div class="col-sm-4"></div> 
            </div> </td> 
           <td>culture_title1</td> 
           <td></td> 
          </tr>
          <tr>
           <td style="width:8%">企业文化英文1</td>
           <td>
            <div class="row">
             <div class="col-sm-8 col-xs-12">
              <input type="text" name="culture_en1" value="<?php echo $data['culture_en1']; ?>" class="form-control" />
             </div>
             <div class="col-sm-4"></div>
            </div> </td>
           <td>culture_en1</td>
           <td></td>
          </tr>
          <tr> 
           <td style="width:8%">企业文化描述1</td> 
           <td> 
            <div class="row"> 
             <div class="col-sm-8 col-xs-12"> 
              <textarea name="culture_des1" class="form-control" style="height:100px;"><?php echo $data['culture_des1']; ?></textarea> 
             </div> 
             <div class="col-sm-4"></div> 
            </div> </td> 
           <td>culture_des1</td> 
           <td></td> 
          </tr>
          <tr> 
           <td style="width:8%">企业文化标题2</td> 
           <td> 
            <div class="row"> 
             <div class="col-sm-8 col-xs-12"> 
              <input type="text" name="culture_title2" value="<?php echo $data['culture_title2']; ?>" class="form-control" /> 
             </div> 
             <div class="col-sm-4"></div> 
            </div> </td> 
           <td>culture_title2</td> 
           <td></td> 
          </tr> 
          <tr> 
           <td style="width:8%">企业文化英文2</td>
           <td> 
            <div class="row"> 
             <div class="col-sm-8 col-xs-12">
              <input type="text" name="culture_en2" value="<?php echo $data['culture_en2']; ?>" class="form-control" />
             </div> 
             <div class="col-sm-4"></div> 
            </div> </td> 
           <td>culture_en2</td>
           <td></td> 
          </tr> 
          <tr> 
           <td style="width:8%">企业文化标题3</td> 
           <td> 
            <div class="row"> 
             <div class="col-sm-8 col-xs-12"> 
              <input type="text" name="culture_title3" value="<?php echo $data['culture_title3']; ?>" class="form-control" /> 
             </div> 
             <div class="col-sm-4"></div> 
            </div> </td> 
           <td>culture_title3</td> 
           <td></td> 
          </tr> 
          <tr> 
           <td style="width:8%">企业文化英文3</td>
           <td> 
            <div class="row"> 
             <div class="col-sm-8 col-xs-12">
              <input type="text" name="culture_en3" value="<?php echo $data['culture_en3']; ?>" class="form-control" />
             </div> 
             <div class="col-sm-4"></div> 
            </div> </td> 
           <td>culture_en3</td>
           <td></td> 
          </tr> 
          <tr> 
           <td style="width:8%">企业文化标题4</td> 
           <td> 
            <div class="row"> 
             <div class="col-sm-8 col-xs-12"> 
              <input type="text" name="culture_title4" value="<?php echo $data['culture_title4']; ?>" class="form-control" /> 
             </div> 
             <div class="col-sm-4"></div> 
            </div> </td> 
           <td>culture_title4</td> 
           <td></td> 
          </tr> 
          <tr> 
           <td style="width:8%">企业文化英文4</td>
           <td> 
            <div class="row"> 
             <div class="col-sm-8 col-xs-12">
              <input type="text" name="culture_en4" value="<?php echo $data['culture_en4']; ?>" class="form-control" />
             </div> 
             <div class="col-sm-4"></div> 
            </div> </td> 
           <td>culture_en4</td>
           <td></td> 
          </tr> 
          <tr> 
           <td style="width:8%">企业文化标题5</td> 
           <td> 
            <div class="row"> 
             <div class="col-sm-8 col-xs-12"> 
              <input type="text" name="culture_title5" value="<?php echo $data['culture_title5']; ?>" class="form-control" /> 
             </div> 
             <div class="col-sm-4"></div> 
            </div> </td> 
           <td>culture_title5</td> 
           <td></td> 
          </tr> 
          <tr> 
           <td style="width:8%">企业文化英文5</td>
           <td> 
            <div class="row"> 
             <div class="col-sm-8 col-xs-12">
              <input type="text" name="culture_en5" value="<?php echo $data['culture_en5']; ?>" class="form-control" />
             </div> 
             <div class="col-sm-4"></div> 
            </div> </td> 
           <td>culture_en5</td>
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
