define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'jstree'], function ($, undefined, Backend, Table, Form, jstree) {
    var Controller = {
        index: function () {
            $(".btn-detail").data("area", ['90%', '90%']);
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'wwh/news/index',
                    add_url: 'wwh/news/add',
                    edit_url: 'wwh/news/edit',
                    del_url: 'wwh/news/del',
                    multi_url: 'wwh/news/multi',
                    table: 'wwh_news',
					dragsort_url:'',
                }
            });
            var table = $("#table");
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'news.id',
                height: $(window).height() - 97,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), },
                        {field: 'newscategory.name', title: __('Newscategory')},
                        {field: 'newsname', title: __('Newsname')},
                        {field: 'n_keywords', title: __('N_keywords')},
                        {field: 'n_description', title: __('N_description')},
						{
                            field: 'summary', sortable: false, title: __('Summary'), formatter: function (value, row, index) {
                                var width = this.width != undefined ? this.width : 250;
                                return "<div style='white-space: nowrap; text-overflow:ellipsis; overflow: hidden; max-width:" + width + "px;'>" + value + "</div>";
                            }
                        },
						{field: 'tjdata', title: __('Tjdata'), searchList: {"0":__('Tjdata 0'),"1":__('Tjdata 1')}, formatter: Table.api.formatter.normal},
                        {field: 'image', title: __('Image'), events: Table.api.events.image, formatter: Table.api.formatter.image, operate: false},
                        {field: 'weigh', title: __('Weigh'), sortable: true,cellStyle: function () {return {css: {"min-width": "65px"}}}},
                        {field: 'time', title: __('Time'), operate:'RANGE', addclass:'datetimerange', sortable: true},
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
                            url: "wwh/newscategory/getjsTree",
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
