define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'jstree'], function ($, undefined, Backend, Table, Form, jstree) {
    var Controller = {
        index: function () {
            $(".btn-detail").data("area", ['90%', '90%']);
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'wwh/cases/index',
                    add_url: 'wwh/cases/add',
                    edit_url: 'wwh/cases/edit',
                    del_url: 'wwh/cases/del',
                    multi_url: 'wwh/cases/multi',
                    table: 'wwh_cases',
                }
            });
            var table = $("#table");
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'cases.id',
                height: $(window).height() - 97,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), },
						{field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'casescategory.name', title: __('Casescategory')},
                        {field: 'casesname', title: __('Casesname')},
                        {field: 'c_keywords', title: __('C_keywords')},
                        {field: 'c_description', title: __('C_description')},
						{field: 'indent_image', title: __('Indent_image'), events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'time', title: __('Time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'views', title: __('Views')},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ],
                queryParams: function (params) {
                    return params;
                },
                onPostBody: function () {
                    $(".btn-detailone").data("area", ['90%', '90%']);
                }
            });
            $(function () {
                $("#channeltree").jstree({
                    "themes": {
						"stripes": true
						},
                    "checkbox": {
                        "keep_selected_style": true,
                    },
                    "plugins": [],
                    "core": {
						"multiple": false,
                        'check_callback': true,
                        "data": {
                            url: "wwh/casescategory/getjsTree",
                        }
                    }
                }).on("select_node.jstree deselect_node.jstree", function (e, data) {
                    $("#table").bootstrapTable("refresh", {query: {categoryids: data.selected.join(",")}});
                });
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));   
            }
        },
    };
    return Controller;
});
