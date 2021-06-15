define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'wwh/market/index' + location.search,
                    add_url: 'wwh/market/add',
                    edit_url: 'wwh/market/edit',
                    del_url: 'wwh/market/del',
                    multi_url: 'wwh/market/multi',
                    table: 'wwh_market',
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
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'citylist', title: __('Citylist'), searchList: {"浙江":__('浙江'),"江苏":__('江苏'),"安徽":__('安徽'),"山东":__('山东'),"福建":__('福建'),"广东":__('广东'),"江西":__('江西'),"北京":__('北京'),"陕西":__('陕西'),"河北":__('河北'),"辽宁":__('辽宁'),"湖南":__('湖南'),"河南":__('河南'),"上海":__('上海'),"云南":__('云南'),"四川":__('四川'),"湖北":__('湖北'),"吉林":__('吉林'),"山西":__('山西'),"重庆":__('重庆'),"广西":__('广西'),"天津":__('天津'),"内蒙古":__('内蒙古'),"贵州":__('贵州'),"黑龙江":__('黑龙江'),"海南":__('海南'),"台湾":__('台湾'),"香港":__('香港'),"新疆":__('新疆'),"甘肃":__('甘肃'),"宁夏":__('宁夏'),"青海":__('青海'),"澳门":__('澳门'),"西藏":__('西藏')}, formatter: Table.api.formatter.normal},
                        {field: 'name', title: __('Name')},
                        {field: 'address', title: __('Address')},
                        {field: 'tel', title: __('Tel')},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
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
        }
    };
    return Controller;
});