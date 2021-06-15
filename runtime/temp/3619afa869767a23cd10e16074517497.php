<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:87:"D:\phpstudy_pro\WWW\faas\public/../application/admin\view\shopro\order\order\index.html";i:1623313469;s:67:"D:\phpstudy_pro\WWW\faas\application\admin\view\layout\default.html";i:1617358420;s:64:"D:\phpstudy_pro\WWW\faas\application\admin\view\common\meta.html";i:1617358420;s:66:"D:\phpstudy_pro\WWW\faas\application\admin\view\common\script.html";i:1617358420;}*/ ?>
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
                                <link rel="stylesheet" href="/assets/addons/shopro/libs/element/element.css">
<link rel="stylesheet" href="/assets/addons/shopro/libs/common.css">
<style>
    .font-size-14 {
        font-size: 14px;
    }

    .font-size-12 {
        font-size: 12px;
    }

    .background-white {
        background: #fff;
    }

    .background-7536D0 {
        background: #7536D0;
    }

    .color-666 {
        color: #666;
    }

    .color-444 {
        color: #444;
    }

    .color-999 {
        color: #999;
    }

    .color-active {
        color: #7536D0;
    }

    .color-active-1 {
        color: #FFB333;
    }

    .margin-left-10 {
        margin-left: 10px;
    }

    .margin-right-20 {
        margin-right: 20px;
    }

    .display-flex {
        display: flex;
        align-items: center;
    }

    .display-flex-c {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .display-flex-column {
        display: flex;
        align-items: center;
        flex-direction: column;
    }

    .common-btn {
        width: 80px;
        line-height: 28px;
        height: 30px;
        border: 1px solid #DCDFE6;
        border-radius: 4px;
        color: #666;
        text-align: center;
        cursor: pointer;
    }

    .common-btn-active {
        color: #fff;
        background: #7536D0;
        border: 1px solid #7536D0;
    }

    .border-bottom {
        border-bottom: 1px solid #E6E6E6;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    label {
        margin: 0;
    }

    /* 选择 */
    .screen {
        border-radius: 6px;
        padding: 10px 20px;
        margin-bottom: 10px;
    }

    .screen-title {
        justify-content: space-between;
        width: 100%;
    }

    .screen-con {
        display: flex;
        flex-wrap: wrap;
        /* margin-top: 20px; */
    }

    .header-select-item,
    .header-input-item,
    .header-button-item {
        margin-right: 30px;
        margin-bottom: 14px;
        width: 242px;
    }

    .header-select-item .el-select {
        width: 100px;
    }

    .header-input-item .header-input-tip {
        margin-right: 14px;
    }

    .header-input-item .el-input {
        width: 176px;
    }

    .order-time {
        padding: 0 6px;
        height: 32px;
        border: 1px solid #DCDFE6;
        border-radius: 4px 0px 0px 4px;
        border-right: none;
        flex-shrink: 0;
    }

    .header-button-select {
        background: #7536D0;
        color: #fff;
        margin-left: 20px;
    }

    .order-refresh {
        width: 32px;
        height: 32px;
        border: 1px solid #E6E6E6;
        border-radius: 4px;
        margin-right: 14px;
        justify-content: center;
    }

    .order-refresh i {
        /* animation-name:go; */
        animation-duration: 2s;
        animation-iteration-count: infinite
    }

    .order-refresh .focusi {
        animation-name: go;

    }

    @keyframes go {
        0% {
            transform: rotateZ(0);
        }

        100% {
            transform: rotateZ(360deg);
        }
    }

    /* table */
    .order-table {
        padding: 20px 20px 30px;
        margin-top: 10px;
    }

    .item-box {
        margin-bottom: 8px;
        color: #444;
        width: 100%;
    }

    .expand-item-container {
        /* flex: 1; */
    }

    .expand-item {
        width: 104px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-right: 1px solid #E6E6E6;
        border-bottom: 1px solid #E6E6E6;
    }

    .expand-item-1 {
        width: 640px;
        border-left: 1px solid #E6E6E6;
    }

    .item-box-item-1-row {
        width: 100%;
        height: 80px;
        padding: 16px 14px 14px;
    }

    .expand-item-4 {
        width: 136px;
    }

    .expand-item-5 {
        width: 94px;
    }

    .expand-item-9 {
        flex-direction: column;
        flex: 1;
    }

    .expand-item-opt {
        flex: 1;
    }

    .item-box-item-more-margin {
        margin-bottom: 4px;
    }

    .item-box-item-name {
        flex-direction: column;
    }

    .item-box-item-name .popover-item {
        margin-bottom: 13px;
    }

    .popover-item-1 {
        height: 30px;
    }

    .item-box-item-detail {
        flex: 1;
        min-width: 80px;
        text-align: center;
    }

    .table-img {
        width: 60px;
        height: 60px;
        margin-right: 14px;
        border: 1px solid #e6e6ee;
        flex-shrink: 0;
    }

    .goods-title {
        width: 526px;
        margin-bottom: 14px;
    }

    .goods-title-ellipsis {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        text-align: left;
    }

    .el-collapse-item__header {
        border: none;
    }

    .el-collapse-item__content {
        padding-bottom: 0;
    }

    .el-radio-button__inner:hover {
        color: #666;
    }

    .el-table__expanded-cell[class*=cell] {
        padding: 0;
    }

    .el-table__fixed-right {
        height: 161px;
    }

    tr {
        margin-bottom: 8px;
    }

    .order-table .el-table__row {
        background: #F9F9F9 !important;
        height: 30px !important;
    }

    .order-table .el-table--enable-row-hover .el-table__body tr:hover>td {
        background: none;
    }

    .order-table .el-table td {
        padding: 0;
        border-top: 1px solid #E6E6E6;
    }

    .el-table::before {
        height: 0;
    }

    .el-table_1_column_2 .cell {
        text-align: left;
        padding: 0;
    }

    .el-table__fixed-right::before,
    .el-table__fixed::before {

        height: 0;

    }

    .operation-btn {
        width: 26px;
        height: 26px;
        padding: 0;
    }

    .order-table .el-table td,
    .order-table .el-table th.is-leaf {
        border-bottom: none;
    }

    .delete-btn {
        width: 90px;
        height: 32px;
        line-height: 32px;
        border: 1px solid #F56C6C;
        border-radius: 4px;
        color: #F56C6C;
        text-align: center;
        float: left;
    }

    .pay-type {
        padding: 0 5px;
        height: 20px;
        background: #E9E2F5;
        border-radius: 4px;
        display: block;
        color: #8A44FC;
        border: 1px solid #B698E7;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .type-alipay {
        border-color: #94BEFB;
        background: #E0EAF9;
        color: #3096FF;
    }

    .type-wechat {
        border-color: #A2E8C4;
        background: #E4F5EC;
        color: #4AD88E;
    }

    .type-score {
        border-color: #F8DCAE;
        background: #FDF5E8;
        color: #F2BA5E;
    }

    .see-detail {
        width: 70px;
        height: 30px;
        line-height: 30px;
        background: rgba(243, 239, 251, 1);
        border: 1px solid rgba(117, 62, 205, 1);
        border-radius: 18px;
    }

    .popover-item-left {
        width: 50px;
        display: block;
    }

    /*.order-dialog*/
    .order-dialog .cell {
        font-size: 12px;
        color: #666;
        font-weight: 400;
    }

    .el-dialog__body {
        padding-top: 10px;
        padding-bottom: 20px;
    }

    .order-dialog .el-dialog__title,
    .el-table thead,
    .has-gutter,
    .order-dialog .el-table,
    .el-form-item__label {
        color: #666;
        font-weight: 400;
    }

    .order-dialog .el-table th {
        background: #F3F3F3;
    }

    .order-dialog .el-table td {
        border-bottom: 1px solid #EBEEF5;
        height: 75px;
        padding: 5px 0;
    }

    .order-dialog .el-table td .cell {
        height: 65px !important;
        justify-content: center;
    }

    .el-form-item {
        margin-bottom: 10px;
    }

    .skill-item,
    .groupon-item {
        /* width: 40px; */
        height: 20px;
        line-height: 20px;
        text-align: center;
        background: rgba(254, 147, 135, 1);
        border-radius: 4px;
        font-size: 12px;
        color: #fff;
        padding: 0 10px;
    }

    .groupon-item {
        background: #A17BDF;
        cursor: pointer;
    }

    .groupon-item-alone {
        cursor: auto;
    }

    .el-dialog {
        border-radius: 6px;
        width: 600px !important;
    }

    .el-dialog__title {
        font-size: 14px;
    }

    .el-input__icon {
        line-height: 32px;
    }

    .margin-right-5 {
        margin-right: 5px;
    }

    .el-form-item__label,
    .el-radio__label,
    .el-form-item__content,
    .el-select-dropdown__item,
    .el-table {
        font-size: 13px;
    }

    .el-form-item {
        margin-bottom: 10px;
    }

    .page-container {
        justify-content: flex-end;
        margin-top: 30px;
    }

    .el-select-dropdown__item.selected {
        color: #7536D0;
    }

    /* select分页 */
    .select-page-container {
        position: relative;
    }

    .select-option-container {
        display: flex;
        align-items: center;
    }

    .select-option-container .option-id {
        width: 100px;
        text-align: left;
    }

    .select-option-container .option-code {
        width: 180px;
        text-align: left;
    }

    .select-option-container .option-name {
        flex: 1;
    }

    .select-pagination {
        position: sticky;
        background: #fff;
        height: 28px;
        top: 0;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 10px;
    }

    .select-pagination .pagination {
        margin: 0;
    }

    .select-pagination .el-pagination__sizes {
        display: none !important;
    }

    .select-pagination-jumper {
        cursor: pointer;
        color: #7438D5;
        margin-left: 8px;
    }

    .order-table .el-table td .cell {
        height: 30px;
        display: flex;
        align-items: center;

    }

    .screen-container {
        line-height: 32px;
        padding: 0 20px;
        background: #FFFFFF;
        box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.06);
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .screen-container-show {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        padding-bottom: 14px;
    }

    .screen-container-left,
    .screen-container-right {
        margin-top: 14px;
        flex-wrap: nowrap;
    }

    .arrow-close i {
        animation-iteration-count: infinite;
        transform: rotateZ(0deg);
    }

    .arrow-close {
        width: 36px;
        height: 32px;
        margin-left: 20px;
        background: #7438D5;
        border-radius: 4px;
        color: #fff;
        font-size: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .arrow-open {
        width: 36px;
        height: 32px;
        margin-left: 20px;
        background: #fff;
        border-radius: 4px;
        color: #7438D5;
        font-size: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #7438D5;

    }

    .arrow-close .arrow-container {
        transform: rotateZ(0deg);
        transition: transform .25s linear;
    }

    .arrow-open .arrow-container {
        transform: rotateZ(180deg);
        transition: transform .25s linear;
    }

    .el-table__empty-block {
        border: 1px solid #e6e6e6;
    }

    .border-right {
        border-right: 1px solid #E6E6E6;

    }

    .border-bottom {
        border-bottom: 1px solid #E6E6E6;

    }

    /* table */
    .order-dialog .el-form--inline {
        padding-top: 16px;
    }

    .dialog-deliver-item {
        align-items: flex-start;
        margin-top: 32px;
    }

    .dialog-deliver-label {
        margin-right: 12px;
        flex-shrink: 0;
    }

    .dialog-deliver-list {
        margin-bottom: 8px;
    }

    .dialog-deliver-list:last-child {
        margin-bottom: 0;
    }

    .dialog-deliver-content label {
        margin-bottom: 0;
    }

    .item-box {
        margin-bottom: 8px;
        color: #444;
    }

    .item-box-item {
        height: 80px;
        width: 104px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .item-box-item-1 {
        width: 683px;
        border-left: 1px solid #E6E6E6;
    }

    .item-box-item-1-box {
        height: 80px;
        border-bottom: 1px solid #E6E6E6;
        padding: 16px 14px 14px;
    }

    .item-box-item-more-margin {
        margin-bottom: 4px;
    }

    .item-box-item-name {
        flex-direction: column;
    }

    .item-box-item-name .el-button {
        padding: 0;
        color: #444;
    }

    .item-box-item-name .popover-item {
        margin-bottom: 13px;
    }

    .popover-item-1 {
        height: 30px;
    }

    .item-box-item-detail {
        flex: 1;
        min-width: 80px;
        text-align: center;
    }

    .order-table .el-table--border {
        border-left: none;
    }

    .el-table--border td,
    .el-table--border th,
    .el-table__body-wrapper .el-table--border.is-scrolling-left~.el-table__fixed {
        border-left: none;
        border-right: none;
    }

    .el-table_1_column_1 {
        border-left: 1px solid #e6e6e6 !important;
    }

    .el-table_1_column_13 {
        border-right: 1px solid #e6e6e6 !important;
    }

    .ellipsis-item {
        max-width: 80px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        -o-text-overflow: ellipsis;
    }

    .choice-item-button {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
    }

    .choice-item-cancel {
        height: 32px;
        line-height: 32px;
        width: 88px;
        text-align: center;
        color: #999;
        cursor: pointer;
    }

    .choice-item-confirm {
        height: 32px;
        line-height: 32px;
        width: 88px;
        text-align: center;
        color: #fff;
        background: #7438D5;
        border-radius: 4px;
        cursor: pointer;
    }

    .ellipsis-1 {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .activity-tags {
        margin-bottom: 0px;
    }

    .el-dialog.batchsend-dialog {
        width: 800px !important;
        left: 50%;
        top: 50%;
        margin: -300px 0 0 -400px !important;
    }

    .el-dialog.batchsend-dialog .el-dialog__header {
        border-bottom: 1px solid #D9D9D9;
        padding: 10px 24px 1px;
    }

    .el-dialog.batchsend-dialog .el-dialog__body {
        padding: 16px 24px 0;
    }

    .el-dialog.batchsend-dialog .el-dialog__footer {
        padding: 16px 20px 24px;
    }

    .el-dialog.batchsend-dialog .el-tabs__header {
        margin-bottom: 0px;
    }

    .el-dialog.batchsend-dialog .el-tabs__nav-wrap::after {
        height: 0;
    }

    .batchsend-refresh {
        margin-right: 10px;
        cursor: pointer;
    }

    .el-dialog.batchSendTypeDialog .el-dialog__header {
        border-bottom: 1px solid #D9D9D9;
        padding: 13px 24px 12px;
    }

    .el-dialog.batchSendTypeDialog .el-dialog__body {
        padding: 24px 24px 60px;
    }

    .el-dialog.batchSendTypeDialog .el-dialog__headerbtn {
        top: 16px;
    }

    .el-dialog.batchSendTypeDialog .select-page-container {
        width: 280px;
    }

    .batchSendTypeTitle {
        font-size: 14px;
        line-height: 22px;
        color: #444444;
        margin-bottom: 20px;
        border: 1px solid #e6e6e6;
        padding: 20px;
        border-radius: 4px;
    }

    .batchSendTypeTitle p {
        line-height: 1;
        margin-bottom: 32px;
    }

    .batchSendTypeTitle:hover {
        background: #F1EBFA;
        border: 1px solid #F1EBFA;
    }

    .batchSendTypeDialog .el-button,
    .batchSendTypeDialog .el-button:hover,
    .batchSendTypeDialog .el-button:active,
    .batchSendTypeDialog .el-button:focus {
        background: #FFF;
        border: 1px solid #DCDFE6;
        color: #606266;
    }

    .batchSendTypeDialog .el-button--primary,
    .batchSendTypeDialog .el-button--primary:hover,
    .batchSendTypeDialog .el-button--primary:active,
    .batchSendTypeDialog .el-button--primary:focus {
        background: #7438D5;
        border-color: #7438D5;
        color: #fff;
    }

    .screen-button {
        margin-right: 10px;
    }

    .screen-button:last-child {
        margin-right: 0;
    }

    .el-date-editor .el-range-separator {
        line-height: 25px;
    }

    [v-cloak] {
        display: none
    }
</style>
<script src="/assets/addons/shopro/libs/vue.js"></script>
<script src="/assets/addons/shopro/libs/element/element.js"></script>
<script src="/assets/addons/shopro/libs/moment.js"></script>
<div id="orderIndex" v-cloak>
    <div class="screen-container">
        <div class="screen-container-show">
            <div class="screen-container-left display-flex">
                <div class="order-refresh display-flex" @click="goOrderRefresh">
                    <i class="el-icon-refresh" :class="focusi?'focusi':''"></i>
                </div>
                <el-radio-group v-model="searchForm.status" fill="#7536D0" @change="reqOrderList(0,10)">
                    <el-radio-button label="all">全部</el-radio-button>
                    <el-radio-button label="cancel">已取消</el-radio-button>
                    <el-radio-button label="invalid">交易关闭</el-radio-button>
                    <el-radio-button label="nopay">待付款</el-radio-button>
                    <el-radio-button label="payed">已支付</el-radio-button>
                    <el-radio-button label="nosend">待发货</el-radio-button>
                    <el-radio-button label="noget">已发货</el-radio-button>
                    <el-radio-button label="nocomment">待评价</el-radio-button>
                    <el-radio-button label="finish">已完成</el-radio-button>
                    <el-radio-button label="aftersale">售后</el-radio-button>
                    <el-radio-button label="refund">退款</el-radio-button>
                </el-radio-group>
            </div>
            <div class="screen-container-right display-flex">
                <div class="display-flex margin-right-20">
                    <div class="color-666 order-time">下单时间</div>
                    <el-date-picker v-model="searchForm.createtime" type="daterange" value-format="yyyy-MM-dd HH:mm:ss"
                        format="yyyy-MM-dd HH:mm:ss" range-separator="至" start-placeholder="开始日期" end-placeholder="结束日期"
                        @change="reqOrderList(0,10)" :picker-options="pickerOptions" align="right" size="small"
                        :default-time="['00:00:00', '23:59:59']">
                    </el-date-picker>
                </div>
                <div v-if="searchForm.status != 'nosend'" class="common-btn cursor-pointer screen-button"
                    @click="goExport('export')">订单导出</div>
                <template v-if="searchForm.status == 'nosend'">
                    <div class="common-btn cursor-pointer screen-button" @click="openBatchSendTypeDialog">批量发货</div>
                    <div class="common-btn cursor-pointer screen-button" @click="goExport('exportDelivery')">导出发货单</div>
                </template>
                <div :class="screenType?'arrow-open':'arrow-close'" @click="changeSwitch">
                    <div class="arrow-container">
                        <i class="el-icon-arrow-down"></i>
                    </div>
                </div>
            </div>
        </div>
        <el-collapse-transition>
            <div class="screen-con" v-if="screenType">
                <div class="display-flex header-select-item">
                    <el-input placeholder="请输入内容" v-model="searchForm.form_1_value" class="input-with-select"
                        size="small">
                        <el-select v-model="searchForm.form_1_key" slot="prepend" placeholder="请选择">
                            <el-option label="订单编号" value="order_sn"></el-option>
                            <el-option label="订单ID" value="id"></el-option>
                            <el-option label="售后订单" value="aftersale_sn"></el-option>
                            <el-option label="支付单号" value="transaction_id"></el-option>
                        </el-select>
                    </el-input>
                </div>
                <div class="display-flex header-select-item">
                    <el-input placeholder="请输入内容" v-model="searchForm.form_2_value" class="input-with-select"
                        size="small">
                        <el-select v-model="searchForm.form_2_key" slot="prepend" placeholder="请选择">
                            <el-option label="会员ID" value="user_id"></el-option>
                            <el-option label="会员昵称" value="nickname"></el-option>
                            <el-option label="手机号" value="user_phone"></el-option>
                            <el-option label="收货人" value="consignee"></el-option>
                            <el-option label="收货人手机号" value="phone"></el-option>
                        </el-select>
                    </el-input>
                </div>
                <div class="display-flex header-input-item">
                    <div class="header-input-tip">订单来源</div>
                    <el-select v-model="searchForm.platform" placeholder="请选择订单来源" size="small">
                        <el-option :label="platform.name" :value="platform.type"
                            v-for="platform in orderScreenList.platform">
                        </el-option>
                    </el-select>
                </div>
                <div class="display-flex header-input-item">
                    <div class="header-input-tip">配送方式</div>
                    <el-select v-model="searchForm.dispatch_type" placeholder="请选择配送方式" size="small">
                        <el-option :label="dispatch.name" :value="dispatch.type"
                            v-for="dispatch in orderScreenList.dispatch_type">
                        </el-option>
                    </el-select>
                </div>
                <div class="display-flex header-input-item">
                    <div class="header-input-tip">订单类型</div>
                    <el-select v-model="searchForm.type" placeholder="请选择订单类型" size="small">
                        <el-option :label="type.name" :value="type.type" v-for="type in orderScreenList.type">
                        </el-option>
                    </el-select>
                </div>
                <div class="display-flex header-input-item">
                    <div class="header-input-tip">支付方式</div>
                    <el-select v-model="searchForm.pay_type" placeholder="请选择支付方式" size="small">
                        <el-option :label="pay.name" :value="pay.type" v-for="pay in orderScreenList.pay_type">
                        </el-option>
                    </el-select>
                </div>
                <div class="display-flex header-input-item">
                    <div class="header-input-tip">营销活动</div>
                    <el-select v-model="searchForm.activity_type" placeholder="请选择营销活动" size="small">
                        <el-option :label="activity.name" :value="activity.type"
                            v-for="activity in orderScreenList.activity_type">
                        </el-option>
                    </el-select>
                </div>
                <div class="display-flex header-input-item">
                    <div class="header-input-tip">商品类型</div>
                    <el-select v-model="searchForm.goods_type" placeholder="请选择商品类型" size="small">
                        <el-option :label="goods.name" :value="goods.type" v-for="goods in orderScreenList.goods_type">
                        </el-option>
                    </el-select>
                </div>
                <div class="display-flex header-input-item" style="width: fit-content;">
                    <div class="header-input-tip">门店订单</div>
                    <div class="display-flex">
                        <el-switch @change="changeStoreId" v-model="store_id_switch" active-color="#7536D0"
                            inactive-color="#eee">
                        </el-switch>
                        <div class="display-flex" v-if="store_id_switch">
                            <div style="margin: 0 14px 0 30px;">选择门店</div>
                            <el-select style="position: relative;" v-model="searchForm.store_id" filterable
                                placeholder="请选择门店" :filter-method="dataFilter" size="small">
                                <el-option v-for="item in selectStoreList" :key="item.name" :label="item.name"
                                    :value="item.id+''">
                                    <div class="display-flex" style="width: 300px;">
                                        <span style="width: 60px;text-align: center;flex-shrink: 0;">{{ item.id
                                            }}</span>
                                        <div class="ellipsis-1" style="width: 80px;flex-shrink: 0;">{{ item.name }}
                                        </div>
                                        <div class="ellipsis-1" style="width: 140px;">
                                            {{item.province_name}}{{item.city_name}}{{item.area_name}}{{item.address}}
                                        </div>
                                    </div>
                                </el-option>
                            </el-select>
                        </div>
                    </div>
                </div>
                <div class="display-flex header-input-item">
                    <div class="header-input-tip">商品名称</div>
                    <el-input placeholder="请输入商品名称" v-model="searchForm.goods_title" size="small"></el-input>
                </div>
                <div class="header-button-item display-flex">
                    <div class="common-btn" @click="screenEmpty">重置</div>
                    <div class="common-btn header-button-select" @click="reqOrderList(0,10)">筛选</div>
                </div>
            </div>
        </el-collapse-transition>
    </div>
    <div class="order-table background-white color-666">
        <el-table :data="orderList" style="width: 100%" ref="multipleTable" tooltip-effect="dark" border
            default-expand-all="true" @selection-change="handleSelectionChange">
            <el-table-column type="expand">
                <template slot-scope="props">
                    <div class="item-box display-flex">
                        <div class="item-box-item-1 border-right" style="flex-direction: column;">
                            <div class="display-flex item-box-item-1-box" v-for="(item,index) in props.row.item">
                                <img class="table-img" :src="Fast.api.cdnurl(item.goods_image)">
                                <div>
                                    <div class="display-flex">
                                        <div class="goods-title goods-title-ellipsis">{{item.goods_title}}</div>
                                    </div>
                                    <div class="color-999 display-flex">
                                        <span v-if="item.goods_sku_text" style="margin-right: 10px;">
                                            规格：{{item.goods_sku_text}}
                                        </span>
                                        <span style="margin-right: 10px;">单价：{{item.goods_price}}元</span>
                                        <span style="margin-right: 10px;">数量：{{item.goods_num}}</span>
                                        <div v-if="item.activity_type" class="display-flex">
                                            <template v-for="(b,a) in item.activity_type_text_arr">
                                                <template v-if="a=='groupon'">
                                                    <div v-if="props.row.btns && props.row.btns.indexOf('groupon')!='-1'"
                                                        class="activity-tags groupon-activity-tag">
                                                        拼团
                                                    </div>
                                                    <div v-if="props.row.btns && props.row.btns.indexOf('groupon_alone')!='-1'"
                                                        class="activity-tags groupon-activity-tag">
                                                        拼团-单独购买
                                                    </div>
                                                </template>
                                                <div v-if="a=='seckill'" class="activity-tags seckill-activity-tag">
                                                    {{b}}</div>
                                                <div v-if="a=='full_reduce' || a=='full_discount' || a=='free_shipping'"
                                                    class="activity-tags full-activity-tag">{{b}}</div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="border-right" style="flex-direction: column;">
                            <div class="item-box-item display-flex-column border-bottom"
                                v-for="(item,index) in props.row.item" style="height: 80px;">
                                <div v-if="props.row.status>0">
                                    <div class="color-active cursor-pointer" @click="openDeliverDialog(props.row,index)"
                                        v-if="item.btns && item.btns.indexOf('send')!='-1' && props.row.status_code!='groupon_ing'">
                                        去发货</div>
                                    <div class="color-active cursor-pointer" @click="openStockDialog(props.row,index)"
                                        v-else-if="item.btns && item.btns.indexOf('send_store')!='-1' && props.row.status_code!='groupon_ing'">
                                        去备货</div>
                                    <div v-else>
                                        <span
                                            v-if="item.dispatch_type=='express'">{{item.dispatch_status_text?item.dispatch_status_text:'-'}}</span>
                                        <span v-else>{{item.status_name}}</span>
                                    </div>
                                </div>
                                <div v-if="props.row.status<=0">-</div>
                            </div>
                        </div>
                        <div class="border-right" style="flex-direction: column;">
                            <div class="item-box-item display-flex-column border-bottom"
                                v-for="(item,index) in props.row.item" style="height: 80px;width:104px">
                                <div v-if="props.row.status>0">
                                    <div :style="{color:item.refund_status<2?'#999':''}">{{item.refund_status_text}}
                                    </div>
                                </div>
                                <div v-else>-</div>
                            </div>
                        </div>
                        <div class="border-right" style="flex-direction: column;">
                            <div class="item-box-item display-flex-column border-bottom"
                                v-for="(item,index) in props.row.item" style="height: 80px;width:136px">
                                <div v-if="props.row.status>0">
                                    <div style="color: #999;margin-bottom: 10px;" v-if="item.aftersale_status==0">
                                        {{item.aftersale_status_text}}</div>
                                    <span v-if="item.btns && item.btns.indexOf('aftersale_info')!=-1"
                                        style="color: #7536D0;cursor: pointer"
                                        @click="viewAftersale(item.ext_arr.aftersale_id)">售后详情</span>
                                </div>
                                <div v-else>-</div>
                            </div>
                        </div>
                        <div class="item-box-item border-bottom border-right"
                            :class="props.row.status<0?'color-999':'color-444'"
                            :style="{'height': props.row.item.length*80+'px'}" style="width: 94px;">
                            {{props.row.status_text}}
                        </div>
                        <div class="item-box-item border-bottom border-right"
                            :style="{'height': props.row.item.length*80+'px'}">
                            <div v-if="props.row.user">
                                <el-popover placement="bottom" width="200" height="80" trigger="hover">
                                    <div class="popover-item-1 display-flex">
                                        <span class="popover-item-left">头像</span><span>:</span>
                                        <img class="margin-left-10" style="width:26px;
                                height:26px;
                                border-radius:50%;" :src="Fast.api.cdnurl(props.row.user.avatar)">
                                    </div>
                                    <div class="popover-item-1 display-flex">
                                        <span class="popover-item-left">ID</span><span>:</span>
                                        <span style="border-bottom: 1px solid #7438D5;height: 24px;
                                        line-height: 24px;cursor: pointer;color: #7438D5;" class="margin-left-10"
                                            @click="goOrderUser(props.row.user.id)">{{props.row.user?props.row.user.id:''}}</span>
                                    </div>
                                    <div v-if="props.row.user && props.row.user.mobile"
                                        class="popover-item-1 display-flex"><span
                                            class="popover-item-left">手机号</span><span>:</span><span
                                            class="margin-left-10">
                                            {{props.row.user.mobile}}</span>
                                    </div>
                                    <el-button type="text" slot="reference">
                                        <div class="color-666 ellipsis-item" style="border-bottom: 1px solid #7438D5;color: #7438D5;height: 30px;
                                        line-height: 30px;" v-if="props.row.user && props.row.user.nickname"
                                            @click="goOrderUser(props.row.user.id)">
                                            {{props.row.user.nickname}}
                                        </div>
                                    </el-button>
                                </el-popover>
                            </div>
                            <div style="color: #F56C6C;" v-else>-</div>
                        </div>
                        <div class="border-right" style="flex-direction: column;">
                            <div class="item-box-item display-flex-column border-bottom"
                                v-for="(item,index) in props.row.item" style="height: 80px;width:114px">
                                <span v-if="item.store">{{item.store.name}}</span>
                                <span v-else>-</span>
                            </div>
                        </div>
                        <div class="item-box-item item-box-item-name border-bottom border-right"
                            :style="{'height': props.row.item.length*80+'px'}">
                            <el-popover placement="bottom" width="200" height="80" trigger="hover">
                                <div class="popover-item">
                                    <span>收货信息:</span>
                                </div>
                                <div class="popover-item">
                                    {{props.row.city_name}}{{props.row.area_name}}{{props.row.address}}
                                </div>
                                <el-button type="text" slot="reference" v-if="props.row.consignee">
                                    <div class="popover-item color-666">
                                        {{props.row.consignee.length>4?props.row.consignee.substr(0,4)+'...':props.row.consignee}}
                                    </div>
                                    <div class="color-666">{{props.row.phone}}</div>
                                </el-button>
                            </el-popover>
                            <div v-if="!props.row.consignee">-</div>
                        </div>
                        <div class="border-right" style="flex-direction: column;">
                            <div class="item-box-item display-flex-column border-bottom"
                                v-for="(item,index) in props.row.item" style="height: 80px;width:104px">
                                {{item.dispatch_type_text}}
                            </div>
                            <!-- {{props.row.item[0].dispatch_type_text?props.row.item[0].dispatch_type_text:props.row.item[0].dispatch_type}} -->
                        </div>
                        <div class="item-box-item display-flex-column border-bottom border-right"
                            :style="{'height': props.row.item.length*80+'px'}" style="width: 136px;">
                            <div class="item-box-item-more-margin">{{props.row.pay_fee}}元</div>
                            <div v-if="props.row.score_amount>0" class="item-box-item-more-margin">
                                +{{props.row.score_amount}}积分</div>
                            <div class="color-active-1" v-if="props.row.dispatch_amount>0">
                                (含运费:{{props.row.dispatch_amount}}元)</div>
                        </div>
                        <div class="item-box-item item-box-item-detail border-bottom border-right"
                            :style="{'height': props.row.item.length*80+'px'}">
                            <div class="color-active cursor-pointer btn-addtabs" @click.stop="goDetail(props.row.id)">
                                查看详情
                            </div>
                        </div>
                    </div>
                </template>
            </el-table-column>
            <el-table-column type="selection" width="40" align="center">
            </el-table-column>
            <el-table-column width="585" label="商品信息">
                <template slot-scope="scope">
                    <div class="display-flex">
                        <span class="font-size-12 color-444 margin-left-10">ID:{{scope.row.id}}</span>
                        <span class="font-size-12 color-999 margin-left-10">
                            订单号:{{scope.row.order_sn}}
                            <span class="font-size-12 color-999 margin-left-10" v-if="scope.row.createtime">
                                下单时间:{{moment(scope.row.createtime*1000).format('YYYY-MM-DD HH:mm:ss')}}
                            </span>
                        </span>
                        <span class="font-size-12 color-active margin-left-10">
                            <span v-if="scope.row.platform_text">{{scope.row.platform_text}}-</span>
                            {{scope.row.type_text}}
                        </span>
                    </div>
                </template>
            </el-table-column>
            <el-table-column width="104" label="发货状态">
                <template slot-scope="scope">
                    <span v-if="scope.row.pay_type=='wallet'"
                        class="font-size-12 margin-left-10 pay-type">{{scope.row.pay_type_text}}</span>
                    <span v-if="scope.row.pay_type=='alipay'"
                        class="font-size-12 margin-left-10 pay-type type-alipay">支付宝支付</span>
                    <span v-if="scope.row.pay_type=='wechat'"
                        class="font-size-12 margin-left-10 pay-type type-wechat">{{scope.row.pay_type_text}}</span>
                    <span v-if="scope.row.pay_type=='score'"
                        class="font-size-12 margin-left-10 pay-type type-score">{{scope.row.pay_type_text}}</span>
                </template>
            </el-table-column>
            <el-table-column width="104" label="退款状态">
            </el-table-column>
            <el-table-column width="136" label="售后状态">
            </el-table-column>
            <el-table-column width="94" label="订单状态">
            </el-table-column>
            <el-table-column width="104" label="下单用户">
            </el-table-column>
            <el-table-column width="114" label="所属门店">
            </el-table-column>
            <el-table-column width="104" label="收货信息">
                <template slot-scope="scope"></template>
            </el-table-column>
            <el-table-column width="104" label="配送方式">
                <template slot-scope="scope"></template>
            </el-table-column>
            <el-table-column width="136" label="支付金额(元)">
                <template slot-scope="scope"></template>
            </el-table-column>
            <el-table-column fixed="right" label="操作">
                <template slot-scope="scope">
                    <div style="width: 100%;display: flex;align-items: center;justify-content: center;"
                        class="color-active cursor-pointer" @click="optRecord(scope.row.id)">操作日志</div>
                </template>
            </el-table-column>
        </el-table>
        <div class="page-container display-flex">
            <el-pagination style="float: right;" @size-change="handleSizeChange" @current-change="handleCurrentChange"
                :current-page="currentPage" :page-sizes="[10, 20, 30, 40]" :page-size="10"
                layout="total, sizes, prev, pager, next, jumper" :total="totalPage">
            </el-pagination>
        </div>
    </div>
    <el-dialog class="order-dialog" title="订单发货" :visible.sync="deliverDialog" width="50%"
        :before-close="closeDeliverDialog">
        <el-table ref="multipleTable" :data="deliverRowTable" tooltip-effect="dark" style="width: 100%" border
            @selection-change="deliverSelectionChange">
            <el-table-column type="selection" width="55">
            </el-table-column>
            <el-table-column label="商品" width="287">
                <template slot-scope="scope">
                    <div style="width: 287px;">
                        <div class="display-flex">
                            <img class="table-img" :src="Fast.api.cdnurl(scope.row.goods_image)">
                            <div style="width:196px">
                                <div style="width:196px" class="goods-title-ellipsis font-size-12">
                                    {{scope.row.goods_title}}
                                </div>
                                <div class="color-999 font-size-12" style="text-align: left;padding-left: 2px;">
                                    <span>规格:</span><span>{{scope.row.goods_sku_text}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </el-table-column>
            <el-table-column prop="goods_num" label="数量" width="70">
            </el-table-column>
            <el-table-column prop="dispatch_status_text" label="状态" width="70">
                <template slot-scope="scope">
                    <span v-if="scope.row.dispatch_status"><span v-if="scope.row.dispatch_status==0"
                            class="color-active">{{scope.row.dispatch_status_text}}</span>
                        <span v-if="scope.row.dispatch_status>0">{{scope.row.dispatch_status_text}}</span></span>
                    <span v-else>-</span>
                </template>
            </el-table-column>
            <el-table-column prop="express_no" label="快递单号">
                <template slot-scope="scope">
                    <span v-if="scope.row.express_no">{{scope.row.express_no}}</span>
                    <span v-else>-</span>
                </template>
            </el-table-column>
        </el-table>
        <div class="dialog-deliver-container">
            <div class="dialog-deliver-item display-flex">
                <div class="dialog-deliver-label">
                    配送信息
                </div>
                <div class="dialog-deliver-content" v-if="deliverRow.item">
                    <div class="dialog-deliver-list">
                        <span>配送方式: </span>
                        <span>{{deliverRow.item?deliverRow.item[0].dispatch_type_text:''}}</span>
                    </div>
                    <div class="dialog-deliver-list">
                        <span>收货人: </span>
                        <span>
                            <span>{{deliverRow.consignee}}</span>
                            <span>{{deliverRow.phone}}</span>
                        </span>
                    </div>
                    <div class="dialog-deliver-list">
                        <span>收货地址: </span>
                        <span>{{deliverRow.city_name}}{{deliverRow.area_name}}{{deliverRow.address}}</span>
                    </div>
                </div>
            </div>
            <div class="dialog-deliver-item display-flex">
                <div class="dialog-deliver-label">
                    发货方式
                </div>
                <div class="dialog-deliver-content">
                    <el-radio-group v-model="deliverType">
                        <el-radio label="input">手动发货</el-radio>
                        <el-radio label="api">一键发货</el-radio>
                    </el-radio-group>
                </div>
            </div>
            <el-form v-if="deliverType=='input'" :inline="true" :model="deliverForm" class="demo-form-inline">
                <el-form-item label="快递公司">
                    <el-select class="select-page-container" v-model="deliverForm.express_code" filterable size="small"
                        placeholder="" :filter-method="deliverFilter" @change="changeExpressName">
                        <el-option v-for="item in deliverCompany" :key="item" :label="item.name" :value="item.code">
                            <div class="select-option-container">
                                <!-- <div class="option-id">{{ item.id }}</div> -->
                                <div class="option-code" :ref="item.code" :data-name="item.name">{{ item.code }}</div>
                                <div class="option-name">{{ item.name }}</div>
                            </div>
                        </el-option>
                        <div class="select-pagination">
                            <el-pagination class="pagination" :page-sizes="[6]" :current-page="deliverCurrentPage"
                                :total="deliverTotalPage" layout="total, sizes, prev, pager,next, jumper"
                                pager-count="5" @current-change="deliverCurrentChange" />
                            </el-pagination>
                            <div class="select-pagination-jumper" @click="getDeliverCompany">跳转</div>
                        </div>
                    </el-select>
                </el-form-item>
                <el-form-item label="快递单号">
                    <el-input size="small" v-model="deliverForm.express_no" placeholder="请输入内容"></el-input>
                </el-form-item>
            </el-form>
        </div>
        <span slot="footer" class="dialog-footer">
            <el-button size="medium" type="primary" @click="reqDeliver" :disabled="!deliverDisabled">
                {{deliverType=='input'?'立即发货':'一键发货'}}
            </el-button>
        </span>
    </el-dialog>
    <!-- 操作日志 -->
    <el-dialog custom-class="log-dialog" title="操作日志" :visible.sync="optRecordDialog">
        <el-table :data="optList" border>
            <el-table-column property="remark" label="事件"></el-table-column>
            <el-table-column property="oper.name" label="员工" width="100"></el-table-column>
            <el-table-column property="createtime" width="200" label="时间">
                <template slot-scope="scope">
                    <span>{{moment(scope.row.createtime*1000).format("YYYY-MM-DD HH:mm:ss")}}</span>
                </template>
            </el-table-column>
        </el-table>
    </el-dialog>
    <!-- 备货 -->
    <el-dialog class="order-dialog" :visible.sync="choice_dialog" width="50%" :before-close="closeStockDialog">
        <div slot="title">
            备货商品<span style="color: #FFB333;">(请尽快通知该门店处理)</span>
        </div>
        <div>
            <el-table :data="choice_list" style="width: 100%" border>
                <el-table-column label="商品" width="287">
                    <template slot-scope="scope">
                        <div style="width: 287px;">
                            <div class="display-flex">
                                <img class="table-img" :src="Fast.api.cdnurl(scope.row.goods_image)">
                                <div style="width:196px">
                                    <div style="width:196px" class="goods-title-ellipsis font-size-12">
                                        {{scope.row.goods_title}}
                                    </div>
                                    <div class="color-999 font-size-12" style="text-align: left;padding-left: 2px;">
                                        <span>规格:</span><span>{{scope.row.goods_sku_text}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column prop="goods_num" label="数量" width="70">
                </el-table-column>
                <el-table-column prop="dispatch_status_text" label="状态" min-width="70">
                    <template slot-scope="scope">
                        <span v-if="scope.row.dispatch_status"><span v-if="scope.row.dispatch_status==0"
                                class="color-active">{{scope.row.dispatch_status_text}}</span>
                            <span v-if="scope.row.dispatch_status>0">{{scope.row.dispatch_status_text}}</span></span>
                        <span v-else>-</span>
                    </template>
                </el-table-column>
            </el-table>
        </div>
        <span slot="footer" class="dialog-footer">
            <el-button class="color-999" size="medium" type="text" @click="closeStockDialog">取消</el-button>
            <el-button size="medium" type="primary" @click="reqSendStore" :disabled="choice_id?false:true">确定
            </el-button>
        </span>
    </el-dialog>
    <!-- 批量发货 -->
    <el-dialog v-dialogDrag custom-class="batchsend-dialog" title="批量发货" :visible.sync="batchSendDialogVisible"
        :before-close="closeBatchSend">
        <span slot="title">
            <el-tabs v-model="batchSendActive">
                <el-tab-pane :label="`全部(${batchSendData.length})`" name="all"></el-tab-pane>
                <el-tab-pane :label="`待发货(${batchSendNosend.length})`" name="nosend"></el-tab-pane>
                <el-tab-pane :label="`成功(${batchSendSuccess.length})`" name="success"></el-tab-pane>
                <el-tab-pane :label="`失败(${batchSendError.length})`" name="error"></el-tab-pane>
            </el-tabs>
        </span>
        <div>
            <div v-if="(batchSendActive=='all' || batchSendActive=='nosend') && loopIndex<batchSendData.length"
                style="margin-bottom: 22px;">
                <div class="display-flex"
                    style="justify-content: space-between;font-size: 16px;color: #434343;margin-bottom: 10px;">
                    <div class="display-flex" v-if="loopIndex<batchSendData.length">
                        <span style="flex-shrink: 0;">当前发货：</span>
                        <span class="ellipsis-1">
                            {{batchSendData[loopIndex].province_name}}{{batchSendData[loopIndex].city_name}}{{batchSendData[loopIndex].area_name}}{{batchSendData[loopIndex].address}}-{{batchSendData[loopIndex].consignee}}
                        </span>
                    </div>
                    <div v-if="loopIndex>=batchSendData.length">发货列表进行完毕</div>
                    <div style="color:#6E3DC8;flex-shrink: 0;">{{loopIndex}}/{{batchSendData.length}}</div>
                </div>
                <el-progress color="#6E3DC8" :percentage="parseInt((loopIndex/batchSendData.length)*100)"></el-progress>
            </div>
            <div>
                <el-table
                    :data="batchSendActive=='all'?batchSendData:(batchSendActive=='nosend'?batchSendNosend:(batchSendActive=='success'?batchSendSuccess:batchSendError))"
                    max-height="400" style="width: 100%" border>
                    <el-table-column prop="id" min-width="80" label="ID">
                        <template slot-scope="scope">
                            <div style="width: 100%;text-align: center;">
                                {{scope.row.id}}
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="order_sn" width="200" label="订单号">
                        <template slot-scope="scope">
                            <div>
                                {{scope.row.order_sn}}
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column min-width="300" label="发货状态" align="center">
                        <template slot-scope="scope">
                            <div>
                                <i v-if="scope.row.batchsend_code==0" class="el-icon-refresh batchsend-refresh"
                                    @click="aloneAgainBatchSend(scope.row)"></i>
                                <span style="color: #ED5B56;"
                                    v-if="scope.row.batchsend_code==0">{{scope.row.batchsend_status}}</span>
                                <span style="color: #70C140;"
                                    v-else-if="scope.row.batchsend_code==1">{{scope.row.batchsend_status}}</span>
                                <span style="color: #8C8C8C;" v-else>待发货</span>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column width="150" label="发货时间">
                        <template slot-scope="scope">
                            <div>
                                <span v-if="scope.row.batchsend_time">
                                    {{moment(scope.row.batchsend_time*1000).format('YYYY-MM-DD HH:mm:ss')}}
                                </span>
                                <span v-else>-</span>
                            </div>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
        </div>
        <span slot="footer" class="batchsend-dialog-footer">
            <template v-if="batchSendActive=='all' || batchSendActive=='nosend'">
                <el-button v-if="batchSendActive=='all' && (loopIndex==0 || batchSendButtonType=='start')"
                    type="primary" @click.stop="openBatchSend('start')">
                    <img style="width:14px;height:14px;margin-right:4px;margin-top: -2px;"
                        src="/assets/addons/shopro/img/order/play.png">
                    开始
                </el-button>
                <el-button v-if="loopIndex!=0 && batchSendButtonType=='suspend'" type="danger"
                    @click.stop="openBatchSend('suspend')">
                    <img style="width:14px;height:14px;margin-right:4px;margin-top: -2px;"
                        src="/assets/addons/shopro/img/order/pause.png">
                    暂停
                </el-button>
                <el-button v-if="loopIndex!=0 && batchSendButtonType=='continue'" type="success"
                    @click.stop="openBatchSend('continue')">
                    <img style="width:14px;height:14px;margin-right:4px;margin-top: -2px;"
                        src="/assets/addons/shopro/img/order/play.png">
                    继续
                </el-button>
            </template>
            <el-button v-if="batchSendActive=='error'" type="primary" @click.stop="againBatchSend()">
                <i class="el-icon-refresh" style="margin-right: 8px;"></i>
                重新发货
            </el-button>
        </span>
    </el-dialog>
    <!-- 批量发货type -->
    <el-dialog v-dialogDrag custom-class="batchSendTypeDialog" title="批量操作" :visible.sync="batchSendTypeVisible"
        :before-close="closeBatchSendType">
        <div>
            <div class="batchSendTypeTitle">
                <p>方法一.如使用导入订单，需完善发货表单物流信息后再上传；</p>
                <div v-if="!deliverByUploadTemplateForm.uploadFile" class="display-flex-c" style="margin-bottom: 35px;">
                    <el-button icon="el-icon-upload2" type="primary" @click="uploadTemplate">导入订单</el-button>
                </div>
                <div v-if="deliverByUploadTemplateForm.uploadFile" class="ellipsis-1"
                    style="margin-bottom: 16px;text-align: center;">{{deliverByUploadTemplateForm.uploadFile}}</div>
                <div v-if="deliverByUploadTemplateForm.uploadFile" class="color-active cursor-pointer"
                    style="margin-bottom: 30px;text-align: center;" @click="uploadTemplate">重新选择文件</div>
                <div class="display-flex" style="justify-content: space-between;">
                    <div class="display-flex">
                        <span style="margin-right: 20px;">选择物流</span>
                        <el-select class="select-page-container" v-model="deliverByUploadTemplateForm.express_code"
                            filterable placeholder="" :filter-method="deliverFilter"
                            @change="deliverByUploadTemplateName">
                            <el-option v-for="item in deliverCompany" :key="item" :label="item.name" :value="item.code">
                                <div class="select-option-container" v-if="item">
                                    <div class="option-code" :ref="item.code+'template'" :data-name="item.name">
                                        {{ item.code}}
                                    </div>
                                    <div class="option-name">{{ item.name }}</div>
                                </div>
                            </el-option>
                            <div class="select-pagination">
                                <el-pagination class="pagination" :page-sizes="[6]" :current-page="deliverCurrentPage"
                                    :total="deliverTotalPage" layout="total, sizes, prev, pager,next, jumper"
                                    pager-count="5" @current-change="deliverCurrentChange" />
                                </el-pagination>
                                <div class="select-pagination-jumper" @click="getDeliverCompany">跳转</div>
                            </div>
                        </el-select>
                    </div>
                    <el-button icon="el-icon-box" @click="deliverByUploadTemplate">发货</el-button>
                </div>
            </div>
            <div class="batchSendTypeTitle">
                <p>方法二.如使用批量发货，需确认 <span class="color-active cursor-pointer"
                        @click="openConfigServices">商城配置-第三方服务</span> 中快递鸟配置完成</p>
                <div class="display-flex-c">
                    <el-button icon="el-icon-document-copy" type="primary" @click="batchSendInit">批量发货</el-button>
                </div>
            </div>
        </div>
    </el-dialog>
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