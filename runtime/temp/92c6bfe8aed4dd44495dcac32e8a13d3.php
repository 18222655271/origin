<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:82:"D:\phpstudy_pro\WWW\faas\public/../application/admin\view\ykquest\problem\add.html";i:1623313688;s:67:"D:\phpstudy_pro\WWW\faas\application\admin\view\layout\default.html";i:1617358420;s:64:"D:\phpstudy_pro\WWW\faas\application\admin\view\common\meta.html";i:1617358420;s:66:"D:\phpstudy_pro\WWW\faas\application\admin\view\common\script.html";i:1617358420;}*/ ?>
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
    .fieldlist dd ins{width: 80%}
    .fieldlist dd input:first-child{width: 90%}
    @media screen and (max-width: 600px) { /*当屏幕尺寸小于600px时，应用下面的CSS样式*/
        .fieldlist dd ins{width:70%}      
    }
    .toption{display: none}
    .ops_0{display: block}
</style>
<form id="add-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="">

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Title'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-title" data-rule="required" class="form-control" name="row[title]" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Option_type'); ?>:</label>
        <div class="col-xs-12 col-sm-8">

            <select  id="c-option_type" data-rule="required" class="form-control selectpicker" name="row[option_type]">
                <?php if(is_array($optionTypeList) || $optionTypeList instanceof \think\Collection || $optionTypeList instanceof \think\Paginator): if( count($optionTypeList)==0 ) : echo "" ;else: foreach($optionTypeList as $key=>$vo): ?>
                <option value="<?php echo $key; ?>" <?php if(in_array(($key), explode(',',""))): ?>selected<?php endif; ?>><?php echo $vo; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>

        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Survey_id'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-survey_id" data-rule="required" data-source="ykquest/survey/index" class="form-control selectpage" name="row[survey_id]" type="text" value="">
        </div>
    </div>
    <?php if(is_array($optionTypeList) || $optionTypeList instanceof \think\Collection || $optionTypeList instanceof \think\Paginator): if( count($optionTypeList)==0 ) : echo "" ;else: foreach($optionTypeList as $key=>$vo): if($key <=3): ?>
    <div class="form-group toption ops_<?php echo $key; ?>">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Toption'); ?>:</label>

        <div class="col-xs-12 col-sm-8 " >

            <dl class="fieldlist" data-template="basictpl" data-name="row[toption<?php echo $key; ?>]">
                <dd><a href="javascript:;" class="btn btn-sm btn-success btn-append"><i class="fa fa-plus"></i> <?php echo __('Append'); ?></a></dd>
                <!--请注意 dd和textarea间不能存在其它任何元素，实际开发中textarea应该添加个hidden进行隐藏-->
                <textarea name="row[toption<?php echo $key; ?>]" class="form-control hidden" cols="30" rows="5">[]</textarea>
            </dl>
            <script id="basictpl" type="text/html">
                <dd class="form-inline">
                    <ins><input type="text" name="<%=name%>[<%=index%>][content]" class="form-control" value="<%=row.content%>" placeholder="选项" size="60"/></ins>
                    <!--下面的两个按钮务必保留-->
                    <span class="btn btn-sm btn-danger btn-remove"><i class="fa fa-times"></i></span>
                    <span class="btn btn-sm btn-primary btn-dragsort"><i class="fa fa-arrows"></i></span>
                </dd>
                </script>

            </div>

        </div>
        <?php endif; endforeach; endif; else: echo "" ;endif; ?> 
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-2"><?php echo __('Weigh'); ?>:</label>
            <div class="col-xs-12 col-sm-8">
                <input id="c-weigh" data-rule="required" class="form-control" name="row[weigh]" type="number" value="0">
            </div>
        </div>


        <div class="form-group layer-footer">
            <label class="control-label col-xs-12 col-sm-2"></label>
            <div class="col-xs-12 col-sm-8">
                <button type="submit" class="btn btn-success btn-embossed disabled"><?php echo __('OK'); ?></button>
                <button type="reset" class="btn btn-default btn-embossed"><?php echo __('Reset'); ?></button>
            </div>
        </div>
    </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo htmlentities($site['version']); ?>"></script>
    </body>
</html>
