define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'upload'], function ($, undefined, Backend, Table, Form, Upload) {

    var Controller = {
        index: function () {

            // 初始化表格参数配置
            Table.api.init({
                search: false,
                showToggle: false,
                pagination: false,
                showColumns: false,
                showExport: false,
                extend: {
                    "index_url": "electronics/index/index",
                    "add_url": "electronics/index/add",
                    "edit_url": "electronics/index/edit",
                    "del_url": "",
                    "multi_url": "",
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                columns: [
                    [
                        {field: 'express_name', title: __('名称')},
                        {field: 'name', title: __('发件人')},
                        {field: 'phone', title: __('手机号')},
                        {field: 'detail', title: __('发货地址')},
                        {field: 'express_code1', title: __('物流编码(快递鸟)')},
                        {field: 'create_time', title: __('添加时间'), formatter: Table.api.formatter.datetime, operate: 'RANGE', addclass: 'datetimerange', sortable: true},
                        {field: 'operate', title: __('Operate'), table: table,
                            events: Table.api.events.operate, formatter: function (value, row, index) {
                                return Table.api.formatter.operate.call(this, value, row, index);
                            }
                         }
                    ]
                ],
                commonSearch: false
            });
            // 为表格绑定事件
            Table.api.bindevent(table);//当内容渲染完成后
            //添加发货示例事件
            $(".btn-send").on('click', function () {
                var url = $(this).data('url');
                var that = this;
                Fast.api.open(url, $(this).attr('title'));
                return false;
            });


        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        send: function () {
            Form.events.selectpage($("form[role=form]"),function(data){
                $("#c-shipping_name").val(data.express_name);
            })
            $(document).on('click', "#add_kuaidi", function () {
                parent.Backend.api.open("electronics/index/add","添加快递");
                return false;
            });
            Form.api.bindevent($("form[role=form]"), function(data, ret){
                //parent.Layer.close(parent.Layer.getFrameIndex(window.name));
                Layer.confirm(data.PrintTemplate, {
                    btn: ['打印','关闭'],//按钮，
                    title:"生成电子面单成功，请打印。",
                }, function(){
                    print(data.PrintTemplate);
                    Layer.closeAll();
                    parent.Layer.closeAll();
                }, function(){
                    Layer.closeAll();
                    parent.Layer.closeAll();
                });
                return false;
            }, function(data, ret){
                Toastr.error("失败");
            }, function(success, error){
                return true;
            });
            //打印电子面单
            function print(html) {
                //判断iframe是否存在，不存在则创建iframe
                var iframe=document.getElementById("print-iframe");
                if(!iframe){
                    var el = document.getElementById("printcontent");
                    iframe = document.createElement('IFRAME');
                    var doc = null;
                    iframe.setAttribute("id", "print-iframe");
                    iframe.setAttribute('style', 'position:absolute;width:0px;height:0px;left:-500px;top:-500px;');
                    document.body.appendChild(iframe);
                    doc = iframe.contentWindow.document;
                    doc.write('<div>'+html+'</div>');
                    doc.close();
                    iframe.contentWindow.focus();
                }
                iframe.contentWindow.print();
                if (navigator.userAgent.indexOf("MSIE") > 0){
                    document.body.removeChild(iframe);
                }
            }
        },
        query:function(){
            Form.events.selectpage($("form[role=form]"),function(data){
                $("#c-shipping_name").val(data.express_name);
            })
            $(document).on('click', "#add_kuaidi", function () {
                parent.Backend.api.open("electronics/index/add","添加快递");
                return false;
            });
            Controller.api.bindevent();
            // 初始化表格参数配置
            Table.api.init({
                search: false,
                showToggle: false,
                pagination: false,
                showColumns: false,
                showExport: false,
                extend: {
                    "index_url": "electronics/index/query",
                    "add_url": "",
                    "edit_url": "",
                    "del_url": "",
                    "multi_url": "",
                }
            });

            var table = $("#table");
            var express_code=$("#c-express_code").val();
            var express_id=$("#c-express_id").val();
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url+"?express_id="+express_id+"&express_code="+express_code,
                columns: [
                    [
                        {field: 'time', title: __('时间'),width: "220px",},
                        {field: 'context', title: __('内容')},
                    ]
                ],
                commonSearch: false
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
            $(document).on('click', "#btn_query", function () {
                var express_code=$("#c-express_code").val();
                var express_id=$("#c-express_id").val();
                var opt = {
                    url: $.fn.bootstrapTable.defaults.extend.index_url+"?express_id="+express_id+"&express_code="+express_code,
                };
                table.bootstrapTable('refresh',opt);
                return false;
            });

        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});