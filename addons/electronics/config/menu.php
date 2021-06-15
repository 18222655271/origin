<?php
/**
 * 菜单配置文件
 */

return [
	    [
	        "type" => "file",
	        "name" => "electronics",
	        "title" => "电子面单",
	        "icon" => "fa fa-list",
	        "condition" => "",
	        "remark" => "",
	        "ismenu" => 1,
	        "sublist" => [
	            [
	                "type" => "file",
	                "name" => "electronics/index",
	                "title" => "快递列表",
	                "icon" => "fa fa-circle-o",
	                "condition" => "",
	                "remark" => "",
	                "ismenu" => 1,
	                "sublist" => [
	                    [
	                        "type" => "file",
	                        "name" => "electronics/index/index",
	                        "title" => "快递物流",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "electronics/index/add",
	                        "title" => "添加",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "electronics/index/edit",
	                        "title" => "修改",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "electronics/index/del",
	                        "title" => "删除",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "electronics/index/send",
	                        "title" => "发货演示代码",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0
	                    ],
	                    [
	                        "type" => "file",
	                        "name" => "electronics/index/multi",
	                        "title" => "批量更新",
	                        "icon" => "fa fa-circle-o",
	                        "condition" => "",
	                        "remark" => "",
	                        "ismenu" => 0
	                    ]
	                ]
	            ],
	            [
	                "type" => "file",
	                "name" => "electronics/index/query",
	                "title" => "物流查询",
	                "icon" => "fa fa-search",
	                "condition" => "",
	                "remark" => "",
	                "ismenu" => 1
	            ]
	        ]
	    ]
	];