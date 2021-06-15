<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:82:"D:\phpstudy_pro\WWW\faas\public/../application/admin\view\ykquest\reply\index.html";i:1623313688;s:67:"D:\phpstudy_pro\WWW\faas\application\admin\view\layout\default.html";i:1617358420;s:64:"D:\phpstudy_pro\WWW\faas\application\admin\view\common\meta.html";i:1617358420;s:66:"D:\phpstudy_pro\WWW\faas\application\admin\view\common\script.html";i:1617358420;}*/ ?>
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
                                <div class="panel panel-default panel-intro">
    <?php echo build_heading(); ?>

    <div class="panel-body">
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="one">
                <div class="widget-body no-padding">
                    <div id="toolbar" class="toolbar">
                        <a href="javascript:;" class="btn btn-primary btn-refresh" title="<?php echo __('Refresh'); ?>" ><i class="fa fa-refresh"></i> </a>
                        <!--                        <a href="javascript:;" class="btn btn-success btn-edit btn-disabled disabled <?php echo $auth->check('ykquest/reply/edit')?'':'hide'; ?>" title="<?php echo __('Edit'); ?>" ><i class="fa fa-pencil"></i> <?php echo __('Edit'); ?></a>
                                                <a href="javascript:;" class="btn btn-danger btn-del btn-disabled disabled <?php echo $auth->check('ykquest/reply/del')?'':'hide'; ?>" title="<?php echo __('Delete'); ?>" ><i class="fa fa-trash"></i> <?php echo __('Delete'); ?></a>
                                                <a class="btn btn-success btn-recyclebin btn-dialog <?php echo $auth->check('ykquest/reply/recyclebin')?'':'hide'; ?>" href="ykquest/reply/recyclebin" title="<?php echo __('Recycle bin'); ?>"><i class="fa fa-recycle"></i> <?php echo __('Recycle bin'); ?></a>
                        -->
                    </div>
                    <table id="table" class="table table-striped table-bordered table-hover table-nowrap"
                           data-operate-edit="false" 
                           data-operate-del="<?php echo $auth->check('ykquest/reply/del'); ?>" 
                           width="100%">
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
<script id="customformtpl" type="text/html">
    <form action="" class="form-commonsearch">
        <div style="border-radius:2px;margin-bottom:10px;padding:15px 20px;">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                    <label class="control-label col-xs-12 col-sm-3" style="line-height: 30px"><?php echo __('problem.Title'); ?></label>
                    <div class="col-xs-12 col-sm-8">
                        <input class="operate" type="hidden" data-name="problem.title" value="="/>
                        <input class="form-control operate"  type="text" name="problem.title" placeholder="请输入查找的标题" value=""/>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 form-group <?php echo $auth->check('ykquest/answerer/index')?'':'hide'; ?>">

                    <label class="control-label col-xs-12 col-sm-3" style="line-height: 30px"><?php echo __('Answerer_id'); ?></label>
                    <div class="col-xs-12 col-sm-8">
                        <input class="operate" type="hidden" data-name="answerer_id" value="="/>
                        <input class="form-control selectpage" data-source="ykquest/answerer/index" data-primary-key="id" data-field="nickname" type="text" name="answerer_id"  value=""/>
                    </div>

                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 form-group <?php echo $auth->check('ykquest/survey/index')?'':'hide'; ?>">

                    <label class="control-label col-xs-12 col-sm-3" style="line-height: 30px"><?php echo __('Survey_id'); ?></label>
                    <div class="col-xs-12 col-sm-8">
                        <input class="operate" type="hidden" data-name="survey_id" value="="/>
                        <input class="form-control selectpage" data-source="ykquest/survey/index" data-primary-key="id" data-field="name" type="text" name="survey_id" value=""/>
                    </div>

                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 form-group">

                    <label class="control-label col-xs-12 col-sm-3" style="line-height: 30px"><?php echo __('Problem.option_type'); ?></label>
                    <div class="col-xs-12 col-sm-8">
                        <input class="operate" type="hidden" data-name="problem.option_type" value="="/>
                        <select class="control-label selectpicker" name="problem.option_type">
                            <option></option>
                            <option value="0"><?php echo __('Option_type 0'); ?></option>
                            <option value="1"><?php echo __('Option_type 1'); ?></option>
                            <option value="3"><?php echo __('Option_type 3'); ?></option>
                        </select>
                    </div>

                </div>

                <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                    <label class="control-label col-xs-12 col-sm-3" style="line-height: 30px"><?php echo __('Createtime'); ?></label>
                    <div class="col-xs-12 col-sm-8">
                        <input type="hidden" class="operate" data-name="createtime" value="RANGE"/>
                        <input type="text" class="form-control datetimerange" name="createtime" value=""/>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                    <div class="row">
                        <label class="control-label col-xs-12 col-sm-3" style="line-height: 30px"></label>
                        <div class="col-xs-3">
                            <input type="submit" class="btn btn-success btn-block" value="提交"/>
                        </div>
                        <div class="col-xs-3">
                            <input type="reset" class="btn btn-primary btn-block" value="重置"/>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
</script>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo htmlentities($site['version']); ?>"></script>
    </body>
</html>
