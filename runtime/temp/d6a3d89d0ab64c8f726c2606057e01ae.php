<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:80:"D:\phpstudy_pro\WWW\faas\public/../application/admin\view\wwh\product\index.html";i:1623313646;s:67:"D:\phpstudy_pro\WWW\faas\application\admin\view\layout\default.html";i:1617358420;s:64:"D:\phpstudy_pro\WWW\faas\application\admin\view\common\meta.html";i:1617358420;s:66:"D:\phpstudy_pro\WWW\faas\application\admin\view\common\script.html";i:1617358420;}*/ ?>
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
                                <style>
    .form-commonsearch .form-group {
        margin-left: 0;
        margin-right: 0;
        padding: 0;
    }

    form.form-commonsearch .control-label {
        padding-right: 0;
    }

    .tdtitle {
        margin-bottom: 5px;
        font-weight: 600;
    }

    #channeltree {
        margin-left: -6px;
    }

    #channelbar .panel-heading {
        height: 55px;
        line-height: 25px;
        font-size: 14px;
    }

    @media (max-width: 1230px) {

        .fixed-table-toolbar .search .form-control {
            display: none;
        }
    }

    @media (min-width: 1200px) {

        #channelbar {
            width: 15%;
        }

        #archivespanel {
            width: 85%;
        }
    }

    .archives-label span.label {
        font-weight: normal;
    }

</style>
<div class="row">
    <div class="col-md-3 hidden-xs hidden-sm" id="channelbar" style="padding-right:0;">
        <div class="panel panel-default panel-intro">
            <div class="panel-heading">
                <div class="panel-lead">
                    <em><?php echo __('Productcategory'); ?></em>
                </div>
            </div>
            <div class="panel-body">
                <div id="channeltree"></div>
            </div>
			<div class="panel-footer ">
            	<a href="javascript:;" class="btn btn-info btn-dialog  <?php echo $auth->check('wwh/productcategory/index')?'':'hide'; ?>" data-url="wwh/productcategory/index" data-title="<?php echo __('Category Manager'); ?>" title="<?php echo __('Category Manager'); ?>"><i class="fa fa-pencil"></i> <?php echo __('Category Manager'); ?></a>
            </div>
        </div>
    </div>
	
	
    <div class="col-xs-12 col-md-9" id="archivespanel">
        <div class="panel panel-default panel-intro">
            <?php echo build_heading(); ?>
            <div class="panel-body">
                <div id="myTabContent" class="tab-content">
                    <div class="tab-pane fade active in" id="one">
                        <div class="widget-body no-padding">
                            <div id="toolbar" class="toolbar">
                                <a href="javascript:;" class="btn btn-primary btn-refresh" title="<?php echo __('Refresh'); ?>">
                                    <i class="fa fa-refresh"></i>
                                </a>
                                <a href="javascript:;" class="btn btn-success btn-add <?php echo $auth->check('wwh/product/add')?'':'hide'; ?>" title="<?php echo __('Add'); ?>">
                                    <i class="fa fa-plus"></i> <?php echo __('Add'); ?></a>
                                <a href="javascript:;" class="btn btn-success btn-edit btn-disabled disabled <?php echo $auth->check('wwh/product/edit')?'':'hide'; ?>"
                                    title="<?php echo __('Edit'); ?>">
                                    <i class="fa fa-pencil"></i> <?php echo __('Edit'); ?></a>
                                <a href="javascript:;" class="btn btn-danger btn-del btn-disabled disabled <?php echo $auth->check('wwh/product/del')?'':'hide'; ?>"
                                    title="<?php echo __('Delete'); ?>">
                                    <i class="fa fa-trash"></i> <?php echo __('Delete'); ?></a>
                            </div>
                            <table id="table" class="table table-striped table-bordered table-hover table-nowrap" 
                                data-operate-edit="<?php echo $auth->check('wwh/product/edit'); ?>" 
                                data-operate-del="<?php echo $auth->check('wwh/product/del'); ?>"
                                width="100%">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo htmlentities($site['version']); ?>"></script>
    </body>
</html>
