define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'jstree'], function ($, undefined, Backend, Table, Form, jstree) {
    var Controller = {
        index: function () {
            $(".btn-detail").data("area", ['90%', '90%']);
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'wwh/product/index',
                    add_url: 'wwh/product/add',
                    edit_url: 'wwh/product/edit',
                    del_url: 'wwh/product/del',
                    multi_url: 'wwh/product/multi',
                    table: 'wwh_product',
                }
            });
            var table = $("#table");
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'product.id',
                height: $(window).height() - 97,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), },
						{field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'productcategory.name', title: __('Productcategory')},
                        {field: 'productname', title: __('Productname')},
                        {field: 'p_keywords', title: __('P_keywords')},
                        {field: 'p_description', title: __('P_description')},
					    {field: 'tjdata', title: __('Tjdata'), searchList: {"0":__('Tjdata 0'),"1":__('Tjdata 1')}, formatter: Table.api.formatter.normal},
						{
                            field: 'model', sortable: false, title: __('Model'), formatter: function (value, row, index) {
                                var width = this.width != undefined ? this.width : 250;
                                return "<div style='white-space: nowrap; text-overflow:ellipsis; overflow: hidden; max-width:" + width + "px;'>" + value + "</div>";
                            }
                        },
						{
                            field: 'description', sortable: false, title: __('Description'), formatter: function (value, row, index) {
                                var width = this.width != undefined ? this.width : 250;
                                return "<div style='white-space: nowrap; text-overflow:ellipsis; overflow: hidden; max-width:" + width + "px;'>" + value + "</div>";
                            }
                        },
                        {field: 'indent_image', title: __('Indent_image'), events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'banner_images', title: __('Banner_images'), events: Table.api.events.image, formatter: Table.api.formatter.images},
                        {field: 'index_image', title: __('Index_image'), events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'size_image', title: __('Size_image'), events: Table.api.events.image, formatter: Table.api.formatter.image},
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
                            url: "wwh/productcategory/getjsTree",
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
