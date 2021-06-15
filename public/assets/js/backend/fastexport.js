define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'bootstrap-select', 'bootstrap-select-lang'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fastexport/index' + location.search,
                    add_url: 'fastexport/add',
                    edit_url: 'fastexport/edit',
                    del_url: 'fastexport/del',
                    multi_url: 'fastexport/multi',
                    testTask: 'fastexport/testTask',
                    performTask: 'fastexport/performTask',
                    table: 'fastexport',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'admin.username', title: __('Admin_id')},
                        {field: 'name', title: __('Name')},
                        {field: 'main_table', title: __('Main Table')},
                        {field: 'file', title: __('File'), formatter: Table.api.formatter.url, operate:false},
                        {field: 'progress', title: __('Progress'), operate:'BETWEEN', formatter: Controller.api.formatter.progress},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {
                            field: 'operate', title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            formatter: Table.api.formatter.operate,
                            buttons: [
                                {
                                    name: 'testTask',
                                    title: __('testTask'),
                                    icon: 'fa fa-wrench',
                                    classname: 'btn btn-success btn-xs btn-ajax',
                                    url: $.fn.bootstrapTable.defaults.extend.testTask,
                                    confirm: '将导出前10条数据,请目检数据是否正常且完整~',
                                    refresh: true,
                                    success: function (data, ret) {

                                        
                                        if (ret.code == 1 && data.direct_export) {
                                            window.location = 'fastexport/performSubTask/subtask_id/1/direct_export/true/task_id/' + data.id;
                                        } else if (ret.msg) {
                                            Layer.alert(ret.msg);
                                        } else {
                                            Layer.alert('未知错误！');
                                        }
                                        return false;
                                    },
                                    error: function (data, ret) {
                                        Layer.alert(ret.msg);
                                        return false;
                                    }
                                },
                                {
                                    name: 'performTask',
                                    title: __('performTask'),
                                    icon: 'fa fa-play-circle',
                                    classname: 'btn btn-xs btn-danger btn-ajax',
                                    url: $.fn.bootstrapTable.defaults.extend.performTask,
                                    confirm: '确认开始执行任务吗？(数据导出属高IO操作，若本任务数据量超过2万，请择服务器相对闲时执行！)',
                                    refresh: true,
                                    success: function (data, ret) {

                                        if (ret.code == 1) {
                                            if (data.direct_export) {
                                                Fast.api.msg('任务已开始,请稍等片刻...')
                                                window.location = 'fastexport/performSubTask/subtask_id/1/direct_export/true/task_id/' + data.id;
                                            } else {
                                                window.open("fastexport/taskControl/task_id/" + data.id,"_blank");
                                            }
                                        } else {
                                            Layer.alert(ret.msg);
                                        }
                                        
                                        return false;
                                    },
                                    error: function (data, ret) {
                                        Layer.alert(ret.msg);
                                        return false;
                                    }
                                },
                            ]
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
            Config.fieldCount = 0;
        },
        edit: function () {
            Controller.api.bindevent();

            // 接受透传过来的数据表字段数据
            Controller.api.tablieField = Config.fields.table_field;
            Controller.api.joinTablieField = Config.fields.join_table_field;
            Controller.api.readyJoinTable = Config.fields.join_table_number;
            Config.fieldCount = Config.fields.field_count;
        },
        taskcontrol: function () {

            if (Config.subtask_page) {
                $('#task_progress').width(Config.task.progress + '%').html(Config.task.progress + '%');
                Controller.api.performSubTask(1);

                // 弹窗使用户触发鼠标点击事件以激活 beforeunload 事件
                // Chrome必须是鼠标点击事件，其他浏览器键盘按下事件也可以激活
                layer.alert('任务进行中,您可以最小化此页,但关闭此页可能导致任务失败！');
                $(window).on('beforeunload', function () {
                    return '导出任务尚未完成，您确定要离开此页吗？';
                });
            }

            $(document).on('click', '.retry_task', function () {
                var subtask_id = $(this).data('subtask_id');
                $.ajax({
                    url: "fastexport/performSubTask/task_id/" + Config.task.id + "/subtask_id/" + subtask_id,
                    dataType: "json",
                    complete: function (ret) {
                        ret = ret.responseJSON;
                        try {
                            if (ret.code == 1) {
                                $('.subtask_' + ret.data.subtask_id + '_status').removeClass('badge-warning').addClass('badge-success').html('已完成');
                                Controller.api.toggleError('hide');
                                Controller.api.checkTask();
                            } else {
                                $('.subtask_' + ret.data.subtask_id + '_status')
                                .removeClass('badge-info')
                                .addClass('badge-warning')
                                .html(ret.msg);
                                Controller.api.toggleError('show');
                            }

                            if (ret.data.error_msg) {
                                Layer.alert(ret.data.subtask_id + '号子任务出错：' + ret.data.error_msg);
                            }
                        } catch (err){
                            Layer.alert('出错啦，请检查网络请求中的报错详情！');
                            return ;
                        }

                        return false;
                    }
                });
            })
        },
        api: {
            formatter: {
                progress: function (value, row) {
                    var span_class = 'text-info';
                    if (value >= 100) {
                        span_class = 'text-success';
                    }
                    if (value >= 5) {
                        return '<a target="_blank" href="fastexport/taskControl/task_id/' + row.id + '" class="searchit ' + span_class + '" data-toggle="tooltip" title="" data-original-title="点击进入任务控制器">' + value + '%</a>';
                    } else {
                        return '<a href="javascript:;" class="searchit ' + span_class + '" data-toggle="tooltip" title="" data-original-title="请先开始任务">' + value + '%</a>';
                    }
                }
            },
            checkTask: function () {
                $.ajax({
                    url: "fastexport/taskFilePack/task_id/" + Config.task.id,
                    complete: function (ret) {
                        ret = ret.responseJSON;
                        try {
                            if (ret.code == 1) {
                                Layer.alert('任务完成，文件已打包，请点击页底按钮进行下载。');
                                $('#task_progress').width('100%').html('100%');
                                Config.task.progress = 100;
                                $(window).unbind('beforeunload');
                                $('.status_button').attr('href', ret.data.file_name).css('display', 'block').fadeIn();
                            } else {
                                $('.status_button').hide();
                            }
                        } catch (err){
                            Layer.alert('出错啦，请检查网络请求中的报错详情！');
                            return ;
                        }
                    }
                })
            },
            toggleError: function (type = 'show') {
                if (type == 'show') {
                    Controller.api.task_error_count++;
                    $('#task_progress').removeClass('progress-bar-success').addClass('progress-bar-danger');
                    $('.status_button').hide();
                } else {
                    // 检查能否取消显示
                    Controller.api.task_error_count = (Controller.api.task_error_count - 1) < 0 ? 0:Controller.api.task_error_count - 1;
                    if (Controller.api.task_error_count <= 0) {
                        $('#task_progress').removeClass('progress-bar-danger').addClass('progress-bar-success');
                    }
                }
            },
            performSubTask: function (current_page) {
                if (Config.subtask_page[current_page]) {

                    for (let i in Config.subtask_page[current_page]){
                        
                        var subtask_id = Config.subtask_page[current_page][i]['id'];
                        $('.subtask_' + subtask_id + '_status').removeClass('badge-success').addClass('badge-info').html('进行中');

                        $.ajax({
                            url: "fastexport/performSubTask/task_id/" + Config.task.id + "/subtask_id/" + subtask_id,
                            dataType: "json",
                            complete: function (ret) {
                                ret = ret.responseJSON;
                                try {
                                    if (ret.code == 1) {
                                        $('.subtask_' + ret.data.subtask_id + '_status').removeClass('badge-info').addClass('badge-success').html('已完成');
                                        Controller.api.toggleError('hide');
                                    } else {
                                        $('.subtask_' + ret.data.subtask_id + '_status')
                                        .removeClass('badge-info')
                                        .addClass('badge-warning')
                                        .html(ret.msg)
                                        .before('<a href="javascript:;" data-subtask_id="' + ret.data.subtask_id + '" class="retry_task">重试</a>');
                                        Controller.api.toggleError('show');
                                    }

                                    if (ret.data.error_msg) {
                                        Layer.alert(ret.data.subtask_id + '号子任务出错：' + ret.data.error_msg);
                                    }
                                } catch (err){
                                    Layer.alert('出错啦，请检查网络请求中的报错详情！');
                                    return ;
                                }

                                Config.task.progress = parseFloat(Config.task.progress) + parseFloat(Config.task.item_subtask_progress);
                                Config.task.progress = (Config.task.progress > 100) ? 100 : Config.task.progress.toFixed(2);
                                $('#task_progress').width(Config.task.progress + '%').html(Config.task.progress + '%');
                                
                                // 检查同页的所有子任务是否都已完成
                                var subtasks = Config.subtask_page[current_page];
                                var is_done = true;
                                for (let i in subtasks){

                                    if (subtasks[i].id == ret.data.subtask_id) {
                                        Config.subtask_page[current_page][i]['status'] = 2;
                                    }

                                    if (Config.subtask_page[current_page][i]['status'] != 2) {
                                        is_done = false;
                                    }
                                }
                                if (is_done) {
                                    Controller.api.performSubTask(current_page + 1);
                                }

                                return false;
                            }
                        });
                    }
                } else {
                    Controller.api.checkTask()
                }
            },
            task_error_count: 0,
            readyJoinTable: 0,
            tablieField: [],
            joinTablieField: [],
            bulidField: function (field, item, name, list_el, show_reduction = true) {

                if (list_el.find("input[name='" + name[0] + "']").length) {
                    return ;
                }

                Config.fieldCount++;

                // <option value="3"' + (item.discerns == 3 ? 'selected="selected"':'') + '>图片</option>\ // 图片导出过慢,弃用
                var el = $('\
                    <div class="form-group" data-field="' + field + '">\
                        <label class="control-label col-xs-12 col-sm-2">' + (show_reduction ? '<a class="reduction_field" href="javascript:;">[-]</a>':'') + field + ':</label>\
                        <div class="col-xs-12 col-sm-9">\
                            <input placeholder="字段标题" class="form-control field_title" name="' + name[0] + '" value="'+item.name+'">\
                            <span class="field_prompt">数据识别:</span>\
                            <select name="' + name[1] + '" class="form-control field_discerns">\
                                <option value="0"' + (item.discerns == 0 ? 'selected="selected"':'') + '>文本</option>\
                                <option value="1"' + (item.discerns == 1 ? 'selected="selected"':'') + '>数字</option>\
                                <option value="2"' + (item.discerns == 2 ? 'selected="selected"':'') + '>日期</option>\
                                <option value="4"' + (item.discerns == 4 ? 'selected="selected"':'') + '>文件</option>\
                                <option value="5"' + (item.discerns == 5 ? 'selected="selected"':'') + '>赋值</option>\
                            </select>\
                            <span class="field_prompt">赋值方案:</span>\
                            <input placeholder="数据识别为“赋值”时,才需填写" class="form-control field_scheme" name="' + name[2] + '" value="'+item.comment+'">\
                        </div>\
                    </div>\
                    ');

                list_el.append(el);
                
            },
            bulidWhereFieldInput: function (field) {
                let input_op_name = 'row[where_field][op][' + field + ']';
                let input_condition_name = 'row[where_field][condition][' + field + ']';

                if ($('#where_field_input').find("input[name='" + input_condition_name + "']").length) {
                    return ;
                }

                // <option value="EXP">表达式 - 支持SQL语法</option>\
                var el = $('\
                    <div class="form-group" data-field="' + field + '">\
                        <label class="control-label col-xs-12 col-sm-3">' + field + ':</label>\
                        <div class="col-xs-12 col-sm-7">\
                            <select name="' + input_op_name + '" class="form-control where_field_op">\
                                <option value="=">等于</option>\
                                <option value="<>">不等于</option>\
                                <option value=">">大于</option>\
                                <option value=">=">大于等于</option>\
                                <option value="<">小于</option>\
                                <option value="<=">小于等于</option>\
                                <option value="LIKE">LIKE - 模糊查询</option>\
                            </select>\
                            <span class="field_prompt">查询条件:</span>\
                            <input placeholder="" class="form-control where_field_condition" name="' + input_condition_name + '" value="">\
                        </div>\
                    </div>\
                    ');

                $('#where_field_input').append(el);
            },
            bulidFieldOption: function (select_el, field, item) {
                let option_text = field + ' - ' + item.name
                $(select_el).append("<option value='" + field + "'>" + option_text + "</option>");
            },
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));

                $(document).on('click', '.reduction_field', function () {
                    $(this).parent().parent().remove()
                    Config.fieldCount--;
                });

                $(document).on('change', '.table_name_as', function () {
                    // 可筛选字段和排序字段更新
                    Controller.api.bulidWhereField();
                });

                /*选择关联表*/
                $(document).on('change', '.s-join_table', function () {

                    // 获取被选表的字段信息->加载到对应的关联主键和取值字段 select 控件上->返回无需额外记录
                    var table_number_id = $(this).data('id');
                    $('#field_list_table_' + table_number_id).html('');
                    var table_name = $(this).val();

                    Fast.api.ajax({
                        url: "fastexport/getTableFields",
                        data: {
                            table: table_name 
                        },
                    }, function (data, ret) {

                        Controller.api.joinTablieField[table_name] = data.fieldlist_comment;

                        // 可筛选字段和排序字段更新
                        Controller.api.bulidWhereField();

                        // 构建关联表字段的 option
                        $('.local_key_table_' + table_number_id).empty();
                        for (let y in data.fieldlist_comment){
                            Controller.api.bulidFieldOption('.local_key_table_' + table_number_id, y, data.fieldlist_comment[y]);
                        }

                        // 构建关联表字段的 option
                        var fields_table_id = '#fields_table_' + table_number_id;
                        $(fields_table_id).empty();
                        for (let y in data.fieldlist_comment){
                            Controller.api.bulidFieldOption(fields_table_id, y, data.fieldlist_comment[y]);
                        }
                        
                        $(fields_table_id).attr('multiple','true');
                        $(fields_table_id).find("option:selected").attr("selected", false);
                        $(fields_table_id).trigger("change");
                        if ($(fields_table_id).data("selectpicker")) {
                            $(fields_table_id).selectpicker('refresh');
                        } else {
                            $(fields_table_id, $("form[role=form]")).selectpicker();
                        }

                        return false;
                    });
                });
                /*选择关联表 end*/

                /*选择关联表字段*/
                $(document).on('change', 'select.s-join_table_field', function (e) {
                    var select_fields = $(this).val();
                    var table_number_id = $(this).data('id');
                    var join_table_name = $('#s-join_table_' + table_number_id).val();

                    // 找到取消勾选的字段，删除配置框
                    var has_been_building_fields = $('#field_list_table_' + table_number_id).children(".form-group");
                    for (var x = 0; x < has_been_building_fields.length; x++) {
                        
                        if (select_fields) {
                            if (!select_fields.includes($(has_been_building_fields[x]).data('field'))) {
                                $(has_been_building_fields[x]).remove()
                                Config.fieldCount--;
                            }
                        } else {
                            $(has_been_building_fields[x]).remove();
                            Config.fieldCount--;
                        }
                    }

                    for(let i in select_fields) {
                        let field = select_fields[i]
                        
                        if (parseInt(field) != 0) {
                            
                            if (!Controller.api.joinTablieField[join_table_name][field]) {
                                Fast.api.msg('字段未找不到啦~');
                                continue;
                            }

                            Controller.api.bulidField(field, Controller.api.joinTablieField[join_table_name][field], {
                                0: 'row[join_table][join_table_'+ table_number_id +'][fields][title][' + field + ']',
                                1: 'row[join_table][join_table_'+ table_number_id +'][fields][discerns][' + field + ']',
                                2: 'row[join_table][join_table_'+ table_number_id +'][fields][scheme][' + field + ']'
                            }, $('#field_list_table_' + table_number_id), false);
                        }
                    }
                });
                /*选择关联表字段 end*/

                /*选择筛选项*/
                $(document).on('change', '#where_field', function () {
                    var where_field = $(this).val();

                    // 找到取消勾选的字段，删除配置框
                    var has_been_building_fields = $('#where_field_input').children(".form-group");

                    for (var x = 0; x < has_been_building_fields.length; x++) {
                        
                        if (where_field) {
                            if (!where_field.includes($(has_been_building_fields[x]).data('field'))) {
                                $(has_been_building_fields[x]).remove()
                            }
                        } else {
                            $(has_been_building_fields[x]).remove();
                        }
                    }

                    for(let i in where_field){
                        if (where_field[i] != '0') {
                            Controller.api.bulidWhereFieldInput(where_field[i]);
                        }
                    }
                });
                /*选择筛选项 end*/

                /*选择源表*/
                $('#c-main_table').on('change', function (e) {
                    var table = $(this).val();
                    var comment = $("#c-main_table option:selected").data('comment');

                    if (table == 'none') {
                        $('#field_config').html('');
                    }

                    // 加载table的字段数据
                    Fast.api.ajax({
                        url: "fastexport/getTableFields",
                        data: {
                            table: table
                        },
                    }, function (data, ret) {

                        Controller.api.tablieField = data.fieldlist_comment;
                        $('#field_config').html('');

                        // 构建字段配置输入框
                        for (let i in data.fieldlist_comment){
                            Controller.api.bulidField(i, data.fieldlist_comment[i], {
                                0:'row[field_config][title][' + i + ']',
                                1:'row[field_config][discerns][' + i + ']',
                                2:'row[field_config][scheme][' + i + ']',
                            }, $('#field_config'));
                        }

                        // 关联表配置的源表字段更新
                        $('.foreign_key').empty();
                        Controller.api.bulidForeignKeyOption();

                        // 可筛选字段和排序字段更新
                        Controller.api.bulidWhereField();

                        return false;
                    });
                });
                /*选择源表 end*/

                /*设置关联表数量*/
                $('#c-join_table_number').on('change', function () {
                    var join_table_number = $(this).val();

                    if (join_table_number == Controller.api.readyJoinTable) {
                        return ;
                    } else if (join_table_number < Controller.api.readyJoinTable) {
                        for (let i = Controller.api.readyJoinTable; i > join_table_number; i--) {
                            $('.join_table_' + i).remove();
                        }
                        Controller.api.readyJoinTable = join_table_number;
                        return ;
                    }

                    // 构建关联表的输入框
                    for (var i = (Number(Controller.api.readyJoinTable) + 1); i <= join_table_number; i++) {

                        // 构建关联表输入框
                        Controller.api.bulidJoinDiv(i);
                        Controller.api.bulidForeignKeyOption('.foreign_key_table_' + i);
                    }
                });
                /*设置关联表数量 end*/
            },
            // 可筛选字段和排序字段更新
            bulidWhereField: function () {

                var source_table = $('#c-main_table').val();
                var where_field = $('#where_field');
                where_field.empty();

                for(let i in Controller.api.tablieField){
                    let option_text = source_table + '.' + i + ' - ' + Controller.api.tablieField[i].name;
                    where_field.append("<option value='" + source_table + '.' + i + "'>" + option_text + "</option>");
                }

                // 查找已选择关联表->获取该表的所有字段渲染
                var join_table_number = $('#c-join_table_number').val();
                for(let i=1; i <= join_table_number; i++) {
                    let table_name = $('#s-join_table_' + i).val();
                    let table_name_as = $('#s-join_table_as_' + i).val();
                    table_name_as = table_name_as ? table_name_as : table_name;
                    if (table_name != 'none') {
                        for(let y in Controller.api.joinTablieField[table_name]){
                            let option_text = table_name_as + '.' + y + ' - ' + Controller.api.joinTablieField[table_name][y].name;
                            where_field.append("<option value='" + table_name_as + '.' + y + "'>" + option_text + "</option>");
                        }
                    }
                }

                // 更新可选的排序字段
                $('#order_field').empty();
                $('#order_field').html(where_field.html());

                $(where_field).trigger("change");
                if ($(where_field).data("selectpicker")) {
                    $(where_field).selectpicker('refresh');
                }
            },
            bulidForeignKeyOption: function (foreign_key_class = null) {

                if (foreign_key_class) {

                    let last_option = $(foreign_key_class +" option:last").val();

                    if (!last_option || last_option == '0') {
                        for (let i in Controller.api.tablieField) {
                            let option_text = i + ' - ' + Controller.api.tablieField[i].name;
                            $(foreign_key_class).append("<option value='" + i + "'>" + option_text + "</option>");
                        }
                    }
                } else {
                    // 更新全部
                    for (let i in Controller.api.tablieField) {
                        let option_text = i + ' - ' + Controller.api.tablieField[i].name;
                        $('.foreign_key').append("<option value='" + i + "'>" + option_text + "</option>");
                    }
                }
                
            },
            bulidJoinDiv: function (table_id) {

                var el = $('\
                <hr class="divider join_table_' + table_id + '">\
                <div class="form-group join_table_' + table_id + '">\
                    <label class="control-label col-xs-12 col-sm-2">选择关联表:</label>\
                    <div class="col-xs-12 col-sm-8">\
                        <select data-id="'+table_id+'" id="s-join_table_'+table_id+'" class="form-control s-join_table" name="row[join_table][join_table_'+table_id+'][table]">\
                            ' + $('#c-main_table').html() + '\
                        </select>\
                    </div>\
                </div>\
                <div class="form-group join_table_' + table_id + '">\
                    <label class="control-label col-xs-12 col-sm-2">关联表别名:</label>\
                    <div class="col-xs-12 col-sm-8">\
                        <input placeholder="非必填,取好别名则关联表可与源表相同" class="form-control table_name_as" id="s-join_table_as_'+table_id+'" name="row[join_table][join_table_'+table_id+'][join_as]" type="text" value="">\
                    </div>\
                </div>\
                <div class="form-group join_table_' + table_id + '">\
                    <label class="control-label col-xs-12 col-sm-2">关联外键:</label>\
                    <div class="col-xs-12 col-sm-8">\
                        <select class="form-control foreign_key foreign_key_table_'+table_id+'" name="row[join_table][join_table_'+table_id+'][foreign_key]">\
                            <option value="0">请选择</option>\
                        </select>\
                        <span class="help-block">若无字段可选,请先选择数据源表</span>\
                    </div>\
                </div>\
                <div class="form-group join_table_' + table_id + '">\
                    <label class="control-label col-xs-12 col-sm-2">关联主键:</label>\
                    <div class="col-xs-12 col-sm-8">\
                        <select class="form-control local_key local_key_table_'+table_id+'" name="row[join_table][join_table_'+table_id+'][local_key]">\
                            <option value="0">请先选择关联表</option>\
                        </select>\
                    </div>\
                </div>\
                <div class="form-group join_table_' + table_id + '">\
                    <label class="control-label col-xs-12 col-sm-2">JOIN类型:</label>\
                    <div class="col-xs-12 col-sm-8">\
                        <select class="form-control" name="row[join_table][join_table_'+table_id+'][join_type]">\
                            <option value="INNER">INNER - 至少一个匹配</option>\
                            <option value="LEFT">LEFT - 左表有匹配</option>\
                            <option value="RIGHT">RIGHT - 右表有匹配</option>\
                            <option value="FULL">FULL - 任意表有匹配</option>\
                        </select>\
                    </div>\
                </div>\
                <div class="form-group join_table_' + table_id + '">\
                    <label class="control-label col-xs-12 col-sm-2">取值字段:</label>\
                    <div class="col-xs-12 col-sm-8">\
                        <select data-id="' + table_id + '" class="form-control s-join_table_field" id="fields_table_'+table_id+'">\
                            <option value="0">请先选择关联表</option>\
                        </select>\
                    </div>\
                </div>\
                <div class="join_table_' + table_id + '" id="field_list_table_'+table_id+'"></div>\
                ');
                $('#join_table').append(el)//插入

                Controller.api.readyJoinTable = table_id;
            },
        }
    };
    return Controller;
});