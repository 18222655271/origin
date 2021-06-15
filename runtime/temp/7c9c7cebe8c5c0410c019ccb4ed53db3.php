<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:79:"D:\phpstudy_pro\WWW\faas\public/../application/admin\view\wwh\config\index.html";i:1623313646;s:67:"D:\phpstudy_pro\WWW\faas\application\admin\view\layout\default.html";i:1617358420;s:64:"D:\phpstudy_pro\WWW\faas\application\admin\view\common\meta.html";i:1617358420;s:66:"D:\phpstudy_pro\WWW\faas\application\admin\view\common\script.html";i:1617358420;}*/ ?>
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
              <li class="active"><a href="#config" data-toggle="tab">站点设置</a></li>
              <li class=""><a href="#banner" data-toggle="tab">栏目Banner图</a></li>
              <li class=""><a href="#footer" data-toggle="tab">底部链接</a></li>
          </ul>
      </div>
      <div class="panel-body">
    <div id="myTabContent" class="tab-content"> 
     <div class="tab-pane fade active in" id="config">
      <div class="widget-body no-padding"> 
       <form id="basic-form" class="edit-form form-horizontal nice-validator n-default n-bootstrap" role="form" data-toggle="validator" method="POST" action="<?php echo url('Admin/wwh/Config/configedit'); ?>" novalidate="novalidate">
        <input type="hidden" name="__token__" value="3d21ee81f41717393f2f12f92c2fa29f" /> 
        <table class="table table-striped"> 
         <tbody> 
          <tr> 
           <td style="width:8%">站点名称</td> 
           <td> 
            <div class="row"> 
             <div class="col-sm-8 col-xs-12"> 
              <input type="text" name="site_name" value="<?php echo $data['site_name']; ?>" class="form-control" /> 
             </div> 
             <div class="col-sm-4"></div> 
            </div> </td> 
           <td>site_name</td> 
           <td></td> 
          </tr> 
          <tr> 
           <td style="width:8%">关键字</td> 
           <td> 
            <div class="row"> 
             <div class="col-sm-8 col-xs-12"> 
              <input type="text" name="keywords" value="<?php echo $data['keywords']; ?>" class="form-control" /> 
             </div> 
             <div class="col-sm-4"></div> 
            </div> </td> 
           <td>keywords</td> 
           <td></td> 
          </tr> 
          <tr> 
           <td style="width:8%">描述</td> 
           <td> 
            <div class="row"> 
             <div class="col-sm-8 col-xs-12"> 
              <textarea name="description" class="form-control" style="height:120px;"><?php echo $data['description']; ?></textarea> 
             </div> 
             <div class="col-sm-4"></div> 
            </div> </td> 
           <td>description</td> 
           <td></td> 
          </tr>
		  <tr>
              <td style="width:8%">logo</td>
              <td>
                  <div class="row">
                      <div class="col-sm-8 col-xs-12">
                          <div class="form-inline">
                              <input id="c-logo" class="form-control" size="35" name="logo" type="text" value="<?php echo $data['logo']; ?>" data-tip="">
                              <span><button type="button" id="plupload-logo" class="btn btn-danger plupload" data-input-id="c-logo" data-mimetype="image/*" data-multiple="false" data-preview-id="p-logo"><i class="fa fa-upload"></i> 上传</button></span>
                              <span><button type="button" id="fachoose-logo" class="btn btn-primary fachoose" data-input-id="c-logo" data-mimetype="image/*" data-multiple="false"><i class="fa fa-list"></i> 选择</button></span>
                              <ul class="row list-inline plupload-preview" id="p-logo"></ul>
                          </div>
                      </div>
                      <div class="col-sm-4"></div>
                  </div>
              </td>
              <td>logo尺寸：195X44</td>
              <td></td>
          </tr>
		  <tr> 
           <td style="width:8%">头部菜单邮箱</td> 
           <td> 
            <div class="row"> 
             <div class="col-sm-8 col-xs-12"> 
              <input type="text" name="email" value="<?php echo $data['email']; ?>" class="form-control" /> 
             </div> 
             <div class="col-sm-4"></div> 
            </div> </td> 
           <td>email</td> 
           <td></td> 
          </tr>
		  <tr> 
           <td style="width:8%">公网安备号</td> 
           <td> 
            <div class="row"> 
             <div class="col-sm-8 col-xs-12"> 
              <input type="text" name="gongwang" value="<?php echo $data['gongwang']; ?>" class="form-control" /> 
             </div> 
             <div class="col-sm-4"></div> 
            </div> </td> 
           <td>gongwang</td> 
           <td></td> 
          </tr>
		  <tr> 
           <td style="width:8%">网站备案号</td> 
           <td> 
            <div class="row"> 
             <div class="col-sm-8 col-xs-12"> 
              <input type="text" name="beian" value="<?php echo $data['beian']; ?>" class="form-control" /> 
             </div> 
             <div class="col-sm-4"></div> 
            </div> </td> 
           <td>beian</td> 
           <td></td> 
          </tr>
		  <tr> 
           <td style="width:8%">版权</td> 
           <td> 
            <div class="row"> 
             <div class="col-sm-8 col-xs-12"> 
              <input type="text" name="copyright" value="<?php echo $data['copyright']; ?>" class="form-control" /> 
             </div> 
             <div class="col-sm-4"></div> 
            </div> </td> 
           <td>copyright</td> 
           <td></td> 
          </tr>
		  <tr>
                            <td style="width:8%">二维码</td>
                            <td>
                                <div class="row">
                                    <div class="col-sm-8 col-xs-12">
                                                                                <div class="form-inline">
                                            <input id="c-image" class="form-control" size="35" name="image" type="text" value="<?php echo $data['image']; ?>" data-tip="">
                                            <span><button type="button" id="plupload-image" class="btn btn-danger plupload" data-input-id="c-image" data-mimetype="image/*" data-multiple="false" data-preview-id="p-image"><i class="fa fa-upload"></i> 上传</button></span>
                                            <span><button type="button" id="fachoose-image" class="btn btn-primary fachoose" data-input-id="c-image" data-mimetype="image/*" data-multiple="false"><i class="fa fa-list"></i> 选择</button></span>
                                            <ul class="row list-inline plupload-preview" id="p-image"></ul>
                                        </div>
                                                                            </div>
                                    <div class="col-sm-4"></div>
                                </div>
                            </td>
							<td>image</td> 
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

        <div class="tab-pane fade " id="banner">
            <div class="widget-body no-padding">
                <form id="basic-form" class="edit-form form-horizontal nice-validator n-default n-bootstrap" role="form" data-toggle="validator" method="POST" action="<?php echo url('Admin/wwh/Config/banneredit'); ?>" novalidate="novalidate">
                    <input type="hidden" name="__token__" value="3d21ee81f41717393f2f12f92c2fa29f" />
                    <table class="table table-striped">
                        <tbody>

                        <tr>
                            <td style="width:8%">产品中心</td>
                            <td>
                                <div class="row">
                                    <div class="col-sm-8 col-xs-12">
                                        <div class="form-inline">
                                            <input id="c-banner1" class="form-control" size="35" name="banner1" type="text" value="<?php echo $data['banner1']; ?>" data-tip="">
                                            <span><button type="button" id="plupload-banner1" class="btn btn-danger plupload" data-input-id="c-banner1" data-mimetype="image/*" data-multiple="false" data-preview-id="p-banner1"><i class="fa fa-upload"></i> 上传</button></span>
                                            <span><button type="button" id="fachoose-banner1" class="btn btn-primary fachoose" data-input-id="c-banner1" data-mimetype="image/*" data-multiple="false"><i class="fa fa-list"></i> 选择</button></span>
                                            <ul class="row list-inline plupload-preview" id="p-banner1"></ul>
                                        </div>
                                    </div>
                                    <div class="col-sm-4"></div>
                                </div>
                            </td>
                            <td>尺寸：1920X500</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="width:8%">解决方案</td>
                            <td>
                                <div class="row">
                                    <div class="col-sm-8 col-xs-12">
                                        <div class="form-inline">
                                            <input id="c-banner2" class="form-control" size="35" name="banner2" type="text" value="<?php echo $data['banner2']; ?>" data-tip="">
                                            <span><button type="button" id="plupload-banner2" class="btn btn-danger plupload" data-input-id="c-banner2" data-mimetype="image/*" data-multiple="false" data-preview-id="p-banner2"><i class="fa fa-upload"></i> 上传</button></span>
                                            <span><button type="button" id="fachoose-banner2" class="btn btn-primary fachoose" data-input-id="c-banner2" data-mimetype="image/*" data-multiple="false"><i class="fa fa-list"></i> 选择</button></span>
                                            <ul class="row list-inline plupload-preview" id="p-banner2"></ul>
                                        </div>
                                    </div>
                                    <div class="col-sm-4"></div>
                                </div>
                            </td>
                            <td>尺寸：1920X500</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="width:8%">服务中心</td>
                            <td>
                                <div class="row">
                                    <div class="col-sm-8 col-xs-12">
                                        <div class="form-inline">
                                            <input id="c-banner3" class="form-control" size="35" name="banner3" type="text" value="<?php echo $data['banner3']; ?>" data-tip="">
                                            <span><button type="button" id="plupload-banner3" class="btn btn-danger plupload" data-input-id="c-banner3" data-mimetype="image/*" data-multiple="false" data-preview-id="p-banner3"><i class="fa fa-upload"></i> 上传</button></span>
                                            <span><button type="button" id="fachoose-banner3" class="btn btn-primary fachoose" data-input-id="c-banner3" data-mimetype="image/*" data-multiple="false"><i class="fa fa-list"></i> 选择</button></span>
                                            <ul class="row list-inline plupload-preview" id="p-banner3"></ul>
                                        </div>
                                    </div>
                                    <div class="col-sm-4"></div>
                                </div>
                            </td>
                            <td>尺寸：1920X500</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="width:8%">新闻中心</td>
                            <td>
                                <div class="row">
                                    <div class="col-sm-8 col-xs-12">
                                        <div class="form-inline">
                                            <input id="c-banner4" class="form-control" size="35" name="banner4" type="text" value="<?php echo $data['banner4']; ?>" data-tip="">
                                            <span><button type="button" id="plupload-banner4" class="btn btn-danger plupload" data-input-id="c-banner4" data-mimetype="image/*" data-multiple="false" data-preview-id="p-banner4"><i class="fa fa-upload"></i> 上传</button></span>
                                            <span><button type="button" id="fachoose-banner4" class="btn btn-primary fachoose" data-input-id="c-banner4" data-mimetype="image/*" data-multiple="false"><i class="fa fa-list"></i> 选择</button></span>
                                            <ul class="row list-inline plupload-preview" id="p-banner4"></ul>
                                        </div>
                                    </div>
                                    <div class="col-sm-4"></div>
                                </div>
                            </td>
                            <td>尺寸：1920X500</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="width:8%">关于我们</td>
                            <td>
                                <div class="row">
                                    <div class="col-sm-8 col-xs-12">
                                        <div class="form-inline">
                                            <input id="c-banner5" class="form-control" size="35" name="banner5" type="text" value="<?php echo $data['banner5']; ?>" data-tip="">
                                            <span><button type="button" id="plupload-banner5" class="btn btn-danger plupload" data-input-id="c-banner5" data-mimetype="image/*" data-multiple="false" data-preview-id="p-banner5"><i class="fa fa-upload"></i> 上传</button></span>
                                            <span><button type="button" id="fachoose-banner5" class="btn btn-primary fachoose" data-input-id="c-banner5" data-mimetype="image/*" data-multiple="false"><i class="fa fa-list"></i> 选择</button></span>
                                            <ul class="row list-inline plupload-preview" id="p-banner5"></ul>
                                        </div>
                                    </div>
                                    <div class="col-sm-4"></div>
                                </div>
                            </td>
                            <td>尺寸：1920X500</td>
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


        <div class="tab-pane fade " id="footer">
            <div class="widget-body no-padding">
                <form id="basic-form" class="edit-form form-horizontal nice-validator n-default n-bootstrap" role="form" data-toggle="validator" method="POST" action="<?php echo url('Admin/wwh/Config/footeredit'); ?>" novalidate="novalidate">
                    <input type="hidden" name="__token__" value="3d21ee81f41717393f2f12f92c2fa29f" />
                    <table class="table table-striped">
                        <tbody>

                        <tr>
                            <td style="width:8%">底部链接</td>
                            <td>
                                <div class="row">
                                    <div class="col-sm-8 col-xs-12">
                                        <textarea name="content" class="form-control" style="height:500px;"><?php echo htmlentities($data['content']); ?></textarea>
                                    </div>
                                    <div class="col-sm-4"></div>
                                </div> </td>
                            <td>content</td>
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
