<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:77:"D:\phpstudy_pro\WWW\faas\public/../application/admin\view\fastexport\add.html";i:1623313658;s:67:"D:\phpstudy_pro\WWW\faas\application\admin\view\layout\default.html";i:1617358420;s:64:"D:\phpstudy_pro\WWW\faas\application\admin\view\common\meta.html";i:1617358420;s:66:"D:\phpstudy_pro\WWW\faas\application\admin\view\common\script.html";i:1617358420;}*/ ?>
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
                                <style type="text/css">
    .field_title{
        display: inline-block;
        width: 19%;
    }
    .field_prompt{
        display: inline-block;
        text-align: center;
        width: 14%;
    }
    .field_discerns{
        display: inline-block;
        width: 12%;
    }
    .field_scheme{
        display: inline-block;
        width: 37%;
    }
    .where_field_op{
        display: inline-block;
        text-align: center;
        width: 32%;
    }
    .where_field_condition{
        display: inline-block;
        width: 52%;
    }
    .order_field{
        display: inline-block;
        width: 70%;
    }
    .order_field_condition{
        display: inline-block;
        width: 28%;
    }
    .tabbar_title{
        padding: 15px;
        background: #e8edf0;
        border-color: #e8edf0;
        margin-bottom: 15px;
    }
    .reduction_field{
        color: #335B64;
        margin-right: 6px;
    }
    .panel-body{
        padding-top: 0;
    }
    .middle_inline{
        display: inline-block;
        vertical-align: middle;
        margin-bottom: 0;
    }
    .kefu_form_control .sp_container {
        width: 100% !important;
    }
    .panel{
        box-shadow: none;
    }
    .xls_max_number .msg-wrap.n-error{
        margin-left: 66px;
    }
    .clear_margin_bottom {
        margin-bottom: 0px;
    }
    .clear_margin_top {
        margin-top: 0px;
    }
    .memory_limit .msg-wrap.n-error{
        margin-left: 50px;
    }
</style>
<div class="row animated fadeInRight">
    <div class="col-md-8 col-md-offset-2">

        <div class="panel panel-default panel-intro">

            <div class="panel-heading tabbable">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#tab1" aria-controls="tab1" role="tab" data-toggle="tab">基础配置</a>
                    </li>
                    <li role="presentation">
                        <a href="#tab2" aria-controls="tab2" role="tab" data-toggle="tab">关联表配置</a>
                    </li>
                    <li role="presentation">
                        <a href="#tab3" aria-controls="tab3" role="tab" data-toggle="tab">数据筛选</a>
                    </li>
                    <li role="presentation">
                        <a href="#tab4" aria-controls="tab4" role="tab" data-toggle="tab">其他配置</a>
                    </li>
                </ul>
            </div>
        
            <div class="panel-body">
                <form id="add-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="<?php echo url('fastexport/add'); ?>">
                    <?php echo token(); ?>
                    <div class="box-body tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="tab1">

                            <div class="form-group">
                                <label class="control-label col-xs-12 col-sm-2"><?php echo __('Name'); ?>:</label>
                                <div class="col-xs-12 col-sm-8">
                                    <input id="c-name" data-rule="required" class="form-control" name="row[name]" type="text" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-xs-12 col-sm-2"><?php echo __('Main Table'); ?>:</label>
                                <div class="col-xs-12 col-sm-8">
                                    <select id="c-main_table" data-rule="required;checkTable" data-rule-checkTable="
                                            function (e) {
                                                if(e.value == 'none') {
                                                    return '请选择数据表';
                                                }
                                            }
                                        " class="form-control" name="row[main_table]">
                                        <option data-comment="" value="none">请选择</option>
                                        <?php if(is_array($tableList) || $tableList instanceof \think\Collection || $tableList instanceof \think\Paginator): $i = 0; $__LIST__ = $tableList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$main_table): $mod = ($i % 2 );++$i;?>
                                            <option data-comment="<?php echo $main_table['comment']; ?>" value="<?php echo $main_table['name']; ?>"><?php echo $main_table['name']; if($main_table['comment']): ?> - <?php echo $main_table['comment']; endif; ?></option>
                                        <?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                    <span class="help-block">请先选择要导出的数据表,随后在生成的列表中配置要导出的字段</span>
                                </div>
                            </div>

                            <div id="field_config"></div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="tab2">
                            <div class="form-group">
                                <label class="control-label col-xs-12 col-sm-2">关联表数量:</label>
                                <div class="col-xs-12 col-sm-8">
                                    <input id="c-join_table_number" data-rule="integer(+0)" class="form-control" type="number" value="0">
                                </div>
                            </div>

                            <div id="join_table"></div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="tab3">
                            <div class="form-group">
                                <label class="control-label col-xs-12 col-sm-2">筛选字段:</label>
                                <div class="col-xs-12 col-sm-8">
                                    <select multiple="true" class="form-control selectpicker" id="where_field">
                                        <option value="0">请选择</option>
                                    </select>
                                    <span class="help-block">请先配置源表和关联表,随后可在此处选择一些字段设置筛选条件</span>
                                </div>
                            </div>

                            <div id="where_field_input"></div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="tab4">
                            <div class="form-group">
                                <label class="control-label col-xs-12 col-sm-3">排序字段:</label>
                                <div class="col-xs-12 col-sm-7">
                                    <select class="form-control order_field" name="row[order_field]" id="order_field">
                                        <option value="0">请选择</option>
                                    </select>
                                    <select class="form-control order_field_condition" name="row[order_type]">
                                        <option value="DESC">倒序(从大到小)</option>
                                        <option value="ASC">正序(从小到大)</option>
                                    </select>
                                    <span class="help-block">请先配置源表和关联表,随后可在此处设置以某字段进行排序</span>
                                </div>
                            </div>
                            <div class="form-group xls_max_number">
                                <label for="c-xls_max_number" class="control-label col-xs-12 col-sm-3">单个xls文件保存:</label>
                                <div class="col-xs-12 col-sm-7">
                                    <div class="input-group input-groupp-md">
                                        <input id="c-xls_max_number" data-rule="required;range(1~30000)" class="form-control" name="row[xls_max_number]" type="number" value="10000">
                                        <span class="input-group-addon">条记录</span>
                                    </div>
                                    <span class="help-block clear_margin_bottom">若导出记录数超出以上设置,则自动分为多个xls文件保存</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-xs-12 col-sm-3">xls文件创建并发:</label>
                                <div class="col-xs-12 col-sm-7">
                                    <input data-rule="required;range(1~100)" class="form-control" name="row[xls_create_concurrent]" type="number" value="2">
                                    <span class="help-block clear_margin_bottom">若有多个xls文件需要准备,此处设置<strong>同一时间</strong>准备的xls文件数</span>
                                    <span class="help-block clear_margin_top">若单个xls文件需保存2万以上数据,普通服务器请设置为`1`(受磁盘IO限制)</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="c-memory_limit" class="control-label col-xs-12 col-sm-3">脚本内存限制:</label>
                                <div class="col-xs-12 col-sm-7">
                                    <div class="input-group memory_limit input-groupp-md">
                                        <input id="c-memory_limit" data-rule="required;checkMemory" data-rule-checkMemory="
                                            function (e) {
                                                var fieldCount = Number(Config.fieldCount);
                                                if (fieldCount <= 0){
                                                    return '请选择要导出的字段';
                                                }
                                                var memory = (fieldCount * Number($('#c-xls_max_number').val())) / 1024;
                                                var memory_limit = Number(e.value);
                                                if (memory >= Number(memory_limit)){
                                                    return '需要更多内存 >' + (memory + 50).toFixed(0) + 'Mb';
                                                }
                                            }
                                        " class="form-control" name="row[memory_limit]" type="number" value="256">
                                        <span class="input-group-addon">Mb</span>
                                    </div>
                                    <span class="help-block clear_margin_bottom">创建单个xls文件时,允许使用的最大内存量</span>
                                    <span class="help-block clear_margin_top">若提示需要更多的内存,请在硬件条件允许的情况下,设置更高的值;或降低单个xls文件保存的记录数</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-xs-12 col-sm-3">导出:</label>
                                <div class="col-xs-12 col-sm-7">
                                    <div class="input-group input-groupp-md">
                                        <input class="form-control" name="row[export_number]" type="number" value="">
                                        <span class="input-group-addon">条记录</span>
                                    </div>
                                    <span class="help-block">需要导出的数据量,不填写为导出全部</span>
                                </div>
                            </div>
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
                    </div>
                </div>
            </div>
        </div>
        <script src="/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo htmlentities($site['version']); ?>"></script>
    </body>
</html>
