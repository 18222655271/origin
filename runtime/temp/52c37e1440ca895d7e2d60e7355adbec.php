<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:80:"D:\phpstudy_pro\WWW\faas\public/../application/admin\view\kefu\config\index.html";i:1623313562;s:67:"D:\phpstudy_pro\WWW\faas\application\admin\view\layout\default.html";i:1617358420;s:64:"D:\phpstudy_pro\WWW\faas\application\admin\view\common\meta.html";i:1617358420;s:66:"D:\phpstudy_pro\WWW\faas\application\admin\view\common\script.html";i:1617358420;}*/ ?>
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
    .panel-body {
        padding-top: 0;
    }
    .middle_inline {
        display: inline-block;
        vertical-align: middle;
        margin-bottom: 0;
    }
    .kefu_form_control .sp_container {
        width: 100% !important;
    }
</style>
<div class="row animated fadeInRight">
    <div class="col-md-8 col-md-offset-2">
        <div class="box box-success">

            <div class="panel-heading tabbable">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#tab1" aria-controls="tab1" role="tab" data-toggle="tab">常规配置</a>
                    </li>
                    <li role="presentation">
                        <a href="#tab2" aria-controls="tab2" role="tab" data-toggle="tab">会话窗口</a>
                    </li>
                    <li role="presentation">
                        <a href="#tab3" aria-controls="tab3" role="tab" data-toggle="tab">自动邀请</a>
                    </li>
                    <li role="presentation">
                        <a href="#tab5" aria-controls="tab5" role="tab" data-toggle="tab">微信小程序</a>
                    </li>
                    <li role="presentation">
                        <a href="javascript:;" class="run_config">运行配置</a>
                    </li>
                </ul>
            </div>

            <div class="panel-body">
                <form id="update-form" role="form" data-toggle="validator" method="POST" action="<?php echo url('kefu.config/update'); ?>">
                    <div class="box-body tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="tab1">
                            <div class="form-group">
                                <label for="chat_name" class="control-label"><?php echo __('Chat name'); ?>:</label>
                                <input type="text" class="form-control" id="chat_name" name="row[chat_name]" value="<?php echo htmlentities($config_list['chat_name']); ?>"/>
                            </div>

                            <div class="form-group">
                                <label class="control-label"><?php echo __('Ecs Exit'); ?>:</label>
                                <?php echo build_radios('row[ecs_exit]', ['1'=>__('Ecs Exit 1'), '0'=>__('Ecs Exit 0')], $config_list['ecs_exit']); ?>
                            </div>

                            <div class="form-group">
                                <label class="control-label"><?php echo __('Send message key'); ?>:</label>
                                <?php echo build_radios('row[send_message_key]', ['1'=>__('Send message key 1'), '0'=>__('Send message key 0')], $config_list['send_message_key']); ?>
                            </div>

                            <div class="form-group">
                                <label class="control-label"><?php echo __('Input status display'); ?>:</label>
                                <?php echo build_radios('row[input_status_display]', ['0'=>__('Input status display 0'), '1'=>__('Input status display 1'), '2'=>__('Input status display 2')], $config_list['input_status_display']); ?>
                            </div>

                            <div class="form-group">
                                <label for="new_user_tip" class="control-label"><?php echo __('New user tip'); ?>:</label>
                                <input type="text" class="form-control" id="new_user_tip" name="row[new_user_tip]" value="<?php echo htmlentities($config_list['new_user_tip']); ?>"/>
                            </div>

                            <div class="form-group">
                                <label for="new_user_msg" class="control-label"><?php echo __('New user msg'); ?>:</label>
                                <textarea rows="3" class="form-control" id="new_user_msg" name="row[new_user_msg]" value=""><?php echo htmlentities($config_list['new_user_msg']); ?></textarea>
                                <span class="help-block">自动发送给新用户的消息，客服代表的欢迎语留空时，使用此处设置的作为欢迎语</span>
                            </div>

                            <div class="form-group">
                                <label class="control-label">客服分配方式:</label>
                                <?php echo build_radios('row[csr_distribution]', ['0' => '按工作强度', '1' => '智能分配', '2' => '轮流分配'], $config_list['csr_distribution']); ?>
                                <span class="help-block" id="distribution_help">
                                    <?php switch($config_list['csr_distribution']): case "0": ?>按工作强度：优先分配给当前接待量最少的客服,若有多个客服接待量相同,则分配给其中最久未进行接待的客服<?php break; case "1": ?>智能分配：根据接待上限和当前接待量，分配给最能接待的客服<?php break; case "2": ?>轮流分配：每次都分配给最久未进行接待的客服<?php break; default: endswitch; ?>
                                    
                                </span>
                            </div>

                            <div class="form-group">
                                <label class="control-label">轨迹保存方案:</label>
                                <?php echo build_radios('row[trajectory_save_cycle]', ['0'=> '保留7天', '1'=> '保留30天', '2'=> '保留60天', '3'=> '永久保留'], $config_list['trajectory_save_cycle']); ?>
                            </div>

                            <div class="form-group">
                                <label class="control-label">知识库自动回复(总开关):</label>
                                <?php echo build_radios('row[kbs_switch]', ['0'=> '关闭', '1'=> '开启'], $config_list['kbs_switch']); ?>
                            </div>

                            <div class="form-group">
                                <label for="new_message_notice" class="control-label">新留言钉钉通知:</label>
                                <textarea rows="3" class="form-control" id="new_message_notice" name="row[new_message_notice]" value=""><?php echo htmlentities($config_list['new_message_notice']); ?></textarea>
                                <span class="help-block">此功能依赖插件《<a target="_blank" href="https://www.fastadmin.net/store/dinghorn.html">钉钉小喇叭</a>》，请在此处填写机器人ID，一行一个</span>
                            </div>

                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="tab2">

                            <div class="form-group">
                                <label class="control-label">收到新消息抖动窗口:</label>
                                <?php echo build_radios('row[new_message_shake]', ['3'=> '抖动', '2'=> '仅客服端抖动', '1'=> '仅用户端抖动', '0'=> '不抖动'], $config_list['new_message_shake']); ?>
                                <span class="help-block">窗口为打开状态时，才会抖动</span>
                            </div>

                            <div class="form-group">
                                <label for="announcement" class="control-label"><?php echo __('Announcement'); ?>:</label>
                                <textarea rows="3" class="form-control" id="announcement" name="row[announcement]" value=""><?php echo htmlentities($config_list['announcement']); ?></textarea>
                            </div>

                            <div class="form-group">
                                <label class="control-label"><?php echo __('Slider images'); ?>:</label>
                                <div class="input-group">
                                    <input id="c-slider_images" class="form-control" size="50" name="row[slider_images]" type="text" value="<?php echo $config_list['slider_images']; ?>">
                                    <div class="input-group-addon no-border no-padding">
                                        <span><button type="button" id="plupload-slider_images" class="btn btn-danger plupload" data-input-id="c-slider_images" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/bmp" data-multiple="true" data-preview-id="p-slider_images"><i class="fa fa-upload"></i> <?php echo __('Upload'); ?></button></span>
                                        <span><button type="button" id="fachoose-slider_images" class="btn btn-primary fachoose" data-input-id="c-slider_images" data-mimetype="image/*" data-multiple="true"><i class="fa fa-list"></i> <?php echo __('Choose'); ?></button></span>
                                    </div>
                                    <span class="msg-box n-right" for="c-slider_images"></span>
                                </div>
                                <ul class="row list-inline plupload-preview" id="p-slider_images"></ul>
                            </div>

                            <div class="form-group">
                                <label class="control-label"><?php echo __('Chat introduces'); ?>:</label>
                                <textarea id="c-chat_introduces" class="form-control editor" rows="5" name="row[chat_introduces]" cols="50"><?php echo htmlentities($config_list['chat_introduces']); ?></textarea>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="tab3">
                            <div class="form-group">
                                <label class="control-label middle_inline">开启自动邀请:</label>
                                <div class="middle_inline">
                                    <?php echo Form::switcher('row[auto_invitation_switch]', $config_list['auto_invitation_switch'], ['color'=>'success']); ?>
                                </div>
                                <span class="help-block">开启时，将在指定时机，为用户弹出“邀请对话”窗口</span>
                            </div>

                            <div class="form-group">
                                <label class="control-label middle_inline">仅首次访问自动邀请:</label>
                                <div class="middle_inline">
                                    <?php echo Form::switcher('row[only_first_invitation]', $config_list['only_first_invitation'], ['color'=>'success']); ?>
                                </div>
                                <span class="help-block">开启时，只在新用户首次访问时自动弹出邀请框</span>
                            </div>

                            <div class="form-group">
                                <label class="control-label middle_inline">仅客服在线自动邀请:</label>
                                <div class="middle_inline">
                                    <?php echo Form::switcher('row[only_csr_online_invitation]', $config_list['only_csr_online_invitation'], ['color'=>'success']); ?>
                                </div>
                                <span class="help-block">开启时，只在有客服在线时自动弹出邀请框</span>
                            </div>

                            <div class="form-group">
                                <label for="auto_invitation_timing" class="control-label">自动邀请时机:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">停留</div>
                                    <input type="text" class="form-control" id="auto_invitation_timing" name="row[auto_invitation_timing]" value="<?php echo htmlentities($config_list['auto_invitation_timing']); ?>"/>
                                    <div class="input-group-addon"><span class="text-warning">秒</span></div>
                                </div>
                                <span class="help-block">用户停留此秒数后，为用户弹出“邀请对话”窗口</span>
                            </div>

                            <div class="form-group">
                                <label class="control-label">邀请框背景:</label>
                                <div class="input-group">
                                    <input id="c-invite_box_img" class="form-control" size="50" name="row[invite_box_img]" type="text" value="<?php echo $config_list['invite_box_img']; ?>">
                                    <div class="input-group-addon no-border no-padding">
                                        <span><button type="button" id="plupload-invite_box_img" class="btn btn-danger plupload" data-input-id="c-invite_box_img" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/bmp" data-multiple="false" data-preview-id="p-invite_box_img"><i class="fa fa-upload"></i> <?php echo __('Upload'); ?></button></span>
                                        <span><button type="button" id="fachoose-invite_box_img" class="btn btn-primary fachoose" data-input-id="c-invite_box_img" data-mimetype="image/*" data-multiple="false"><i class="fa fa-list"></i> <?php echo __('Choose'); ?></button></span>
                                    </div>
                                    <span class="msg-box n-right" for="c-invite_box_img"></span>
                                </div>
                                <ul class="row list-inline plupload-preview" id="p-invite_box_img"></ul>
                                <span class="help-block">建议大小：400px*180px</span>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="tab5">

                            <div class="form-group">
                                <label for="wechat_app_id" class="control-label">小程序appId:</label>
                                <input type="text" class="form-control" id="wechat_app_id" name="row[wechat_app_id]" value="<?php echo htmlentities($config_list['wechat_app_id']); ?>"/>
                            </div>

                            <div class="form-group">
                                <label for="wechat_app_secret" class="control-label">小程序appSecret:</label>
                                <input type="text" class="form-control" id="wechat_app_secret" name="row[wechat_app_secret]" value="<?php echo htmlentities($config_list['wechat_app_secret']); ?>"/>
                            </div>

                            <hr class="divider">

                            <span class="help-block">
                                请登录小程序<a target="_blank" href="https://mp.weixin.qq.com/">MP后台</a>->开发->开发设置->消息推送->继续以下设置->请保存设置后微信端再进行验证
                            </span>

                            <div class="form-group">
                                <label for="wechat_app_secret" class="control-label">服务器地址(url):</label>
                                <input type="text" class="form-control" readonly="readonly" value="http://您的域名/api/kefu/acceptWxMsg"/>
                            </div>

                            <div class="form-group">
                                <label for="wechat_token" class="control-label">通信令牌(token):</label>
                                <input type="text" placeholder="英文或数字,3-32字符" class="form-control" id="wechat_token" name="row[wechat_token]" value="<?php echo htmlentities($config_list['wechat_token']); ?>"/>
                                <span class="help-block">任意填写，需与MP后台->开发->开发设置->消息推送中的Token一致</span>
                            </div>

                            <div class="form-group">
                                <label for="wechat_encodingkey" class="control-label">解密密钥(EncodingAESKey):</label>
                                <input type="text" placeholder="英文或数字,43位字符" class="form-control" id="wechat_encodingkey" name="row[wechat_encodingkey]" value="<?php echo htmlentities($config_list['wechat_encodingkey']); ?>"/>
                                <span class="help-block">任意填写，需与MP后台->开发->开发设置->消息推送中的EncodingAESKey一致</span>
                            </div>

                            <div class="form-group">
                                <label for="wechat_app_secret" class="control-label">消息加密模式:</label>
                                <input type="text" class="form-control" readonly="readonly" value="安全模式"/>
                            </div>

                            <div class="form-group">
                                <label for="wechat_app_secret" class="control-label">数据格式:</label>
                                <input type="text" class="form-control" readonly="readonly" value="JSON"/>
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 15px;">
                            <button type="submit" class="btn btn-success"><?php echo __('Submit'); ?></button>
                            <button type="reset" class="btn btn-default"><?php echo __('Reset'); ?></button>
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
