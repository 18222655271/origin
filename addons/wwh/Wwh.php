<?php

namespace addons\wwh;

use app\common\library\Menu;
use think\Addons;
use think\Db;

/**
 * 插件
 */
class Wwh extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu = [
        [
            "name" => "wwh",
            "title" => "企业官网",
            'sublist' => [
        [
            "name" => "wwh/config",
            "title" => "站点配置",
            'icon'  => 'fa fa-gears',
            "ismenu" => 1,
            'weigh'   => '100',
            "sublist" => [
                [
                    "name" => "wwh/config/index",
                    "title" => "查看"
                ],
                [
                    "name" => "wwh/config/configedit",
                    "title" => "站点设置修改"
                ],
                [
                    "name" => "wwh/config/banneredit",
                    "title" => "栏目Banner修改"
                ],
                [
                    "name" => "wwh/config/footeredit",
                    "title" => "底部链接修改"
                ]
            ]
        ],
        [
            "name" => "shouye",
            "title" => "首页",
            'icon'  => 'fa fa-home',
            "ismenu" => 1,
            'weigh'   => '90',
            "sublist" => [
                [
                    "name" => "wwh/home",
                    "title" => "首页配置",
                    'icon'  => 'fa fa-cog',
                    "ismenu" => 1,
                    'weigh'   => '10',
                    "sublist" => [
                        [
                            "name" => "wwh/home/index",
                            "title" => "查看"
                        ],
                        [
                            "name" => "wwh/home/enterprise",
                            "title" => "关于我们修改"
                        ]
                    ]
                ],
                [
                    "name" => "wwh/banner",
                    "title" => "Banner图",
                    'icon'  => 'fa fa-photo',
                    "ismenu" => 1,
                    'weigh'   => '9',
                    "sublist" => [
                        [
                            "name" => "wwh/banner/index",
                            "title" => "查看"
                        ],
                        [
                            "name" => "wwh/banner/add",
                            "title" => "添加"
                        ],
                        [
                            "name" => "wwh/banner/edit",
                            "title" => "编辑"
                        ],
                        [
                            "name" => "wwh/banner/del",
                            "title" => "删除"
                        ]
                    ]
                ]
            ]
        ],
        [
            "name" => "wwh/product",
            "title" => "产品中心",
            'icon'  => 'fa fa-product-hunt',
            "ismenu" => 1,
            'weigh'   => '80',
            "sublist" => [
                [
                    "name" => "wwh/product/index",
                    "title" => "查看"
                ],
                [
                    "name" => "wwh/product/add",
                    "title" => "添加"
                ],
                [
                    "name" => "wwh/product/edit",
                    "title" => "编辑"
                ],
                [
                    "name" => "wwh/product/del",
                    "title" => "删除"
                ]
            ]
        ],
        [
            "name" => "wwh/productcategory",
            "title" => "产品分类",
            "ismenu" => 0,
            'weigh'   => '79',
            "sublist" => [
                [
                    "name" => "wwh/productcategory/index",
                    "title" => "查看"
                ],
                [
                    "name" => "wwh/productcategory/add",
                    "title" => "添加"
                ],
                [
                    "name" => "wwh/productcategory/edit",
                    "title" => "编辑"
                ],
                [
                    "name" => "wwh/productcategory/del",
                    "title" => "删除"
                ]
            ]
        ],
        [
            "name" => "wwh/cases",
            "title" => "解决方案",
            'icon'  => 'fa fa-external-link',
            "ismenu" => 1,
            'weigh'   => '70',
            "sublist" => [
                [
                    "name" => "wwh/cases/index",
                    "title" => "查看"
                ],
                [
                    "name" => "wwh/cases/add",
                    "title" => "添加"
                ],
                [
                    "name" => "wwh/cases/edit",
                    "title" => "编辑"
                ],
                [
                    "name" => "wwh/cases/del",
                    "title" => "删除"
                ]
            ]
        ],
        [
            "name" => "wwh/casescategory",
            "title" => "方案分类",
            "ismenu" => 0,
            'weigh'   => '69',
            "sublist" => [
                [
                    "name" => "wwh/casescategory/index",
                    "title" => "查看"
                ],
                [
                    "name" => "wwh/casescategory/add",
                    "title" => "添加"
                ],
                [
                    "name" => "wwh/casescategory/edit",
                    "title" => "编辑"
                ],
                [
                    "name" => "wwh/casescategory/del",
                    "title" => "删除"
                ]
            ]
        ],
        [
            "name" => "fuwu",
            "title" => "服务中心",
            'icon'  => 'fa fa-volume-control-phone',
            "ismenu" => 1,
            'weigh'   => '60',
            "sublist" => [
                [
                    "name" => "wwh/service",
                    "title" => "服务策略",
                    'icon'  => 'fa fa-street-view',
                    "ismenu" => 1,
                    'weigh'   => '10',
                    "sublist" => [
                        [
                            "name" => "wwh/service/index",
                            "title" => "查看"
                        ],
                        [
                            "name" => "wwh/service/serviceedit",
                            "title" => "服务策略修改"
                        ]
                    ]
                ],
                [
                    "name" => "wwh/market",
                    "title" => "营销网络",
                    'icon'  => 'fa fa-map-marker',
                    "ismenu" => 1,
                    'weigh'   => '9',
                    "sublist" => [
                        [
                            "name" => "wwh/market/index",
                            "title" => "查看"
                        ],
                        [
                            "name" => "wwh/market/add",
                            "title" => "添加"
                        ],
                        [
                            "name" => "wwh/market/edit",
                            "title" => "编辑"
                        ],
                        [
                            "name" => "wwh/market/del",
                            "title" => "删除"
                        ]
                    ]
                ],
                [
            "name" => "wwh/download",
            "title" => "下载中心",
            'icon'  => 'fa fa-arrow-circle-down',
            "ismenu" => 1,
            'weigh'   => '8',
            "sublist" => [
                [
                    "name" => "wwh/download/index",
                    "title" => "查看"
                ],
                [
                    "name" => "wwh/download/add",
                    "title" => "添加"
                ],
                [
                    "name" => "wwh/download/edit",
                    "title" => "编辑"
                ],
                [
                    "name" => "wwh/download/del",
                    "title" => "删除"
                ]
            ]
        ],
        [
            "name" => "wwh/downloadcategory",
            "title" => "下载分类",
            "ismenu" => 0,
            'weigh'   => '7',
            "sublist" => [
                [
                    "name" => "wwh/downloadcategory/index",
                    "title" => "查看"
                ],
                [
                    "name" => "wwh/downloadcategory/add",
                    "title" => "添加"
                ],
                [
                    "name" => "wwh/downloadcategory/edit",
                    "title" => "编辑"
                ],
                [
                    "name" => "wwh/downloadcategory/del",
                    "title" => "删除"
                ]
            ]
        ]
            ]
        ],
        [
            "name" => "wwh/news",
            "title" => "新闻中心",
            'icon'  => 'fa fa-newspaper-o',
            "ismenu" => 1,
            'weigh'   => '50',
            "sublist" => [
                [
                    "name" => "wwh/news/index",
                    "title" => "查看"
                ],
                [
                    "name" => "wwh/news/add",
                    "title" => "添加"
                ],
                [
                    "name" => "wwh/news/del",
                    "title" => "删除"
                ],
                [
                    "name" => "wwh/news/edit",
                    "title" => "编辑"
                ]
            ]
        ],
        [
            "name" => "wwh/newscategory",
            "title" => "新闻分类",
            "ismenu" => 0,
            'weigh'   => '49',
            "sublist" => [
                [
                    "name" => "wwh/newscategory/index",
                    "title" => "查看"
                ],
                [
                    "name" => "wwh/newscategory/add",
                    "title" => "添加"
                ],
                [
                    "name" => "wwh/newscategory/edit",
                    "title" => "编辑"
                ],
                [
                    "name" => "wwh/newscategory/del",
                    "title" => "删除"
                ]
            ]
        ],
        [
            "name" => "guanyu",
            "title" => "关于我们",
            'icon'  => 'fa fa-user-circle',
            "ismenu" => 1,
            'weigh'   => '40',
            "sublist" => [
                [
                    "name" => "wwh/about",
                    "title" => "公司概况",
                    'icon'  => 'fa fa-group',
                    "ismenu" => 1,
                    'weigh'   => '10',
                    "sublist" => [
                        [
                            "name" => "wwh/about/index",
                            "title" => "查看"
                        ],
                        [
                            "name" => "wwh/about/about",
                            "title" => "企业介绍修改"
                        ],
                        [
                            "name" => "wwh/about/culture",
                            "title" => "企业文化修改"
                        ]
                    ]
                ],
                [
                    "name" => "wwh/development",
                    "title" => "发展历程",
                    'icon'  => 'fa fa-calendar-check-o',
                    "ismenu" => 1,
                    'weigh'   => '10',
                    "sublist" => [
                        [
                            "name" => "wwh/development/index",
                            "title" => "查看"
                        ],
                        [
                            "name" => "wwh/development/add",
                            "title" => "添加"
                        ],
                        [
                            "name" => "wwh/development/edit",
                            "title" => "编辑"
                        ],
                        [
                            "name" => "wwh/development/del",
                            "title" => "删除"
                        ]
                    ]
                ],
                [
                    "name" => "wwh/honor",
                    "title" => "荣誉资质",
                    'icon'  => 'fa fa-picture-o',
                    "ismenu" => 1,
                    'weigh'   => '9',
                    "sublist" => [
                        [
                            "name" => "wwh/honor/index",
                            "title" => "查看"
                        ],
                        [
                            "name" => "wwh/honor/add",
                            "title" => "添加"
                        ],
                        [
                            "name" => "wwh/honor/edit",
                            "title" => "编辑"
                        ],
                        [
                            "name" => "wwh/honor/del",
                            "title" => "删除"
                        ]
                    ]
                ],
                [
                    "name" => "wwh/position",
                    "title" => "加入我们",
                    'icon'  => 'fa fa-handshake-o',
                    "ismenu" => 1,
                    'weigh'   => '8',
                    "sublist" => [
                        [
                            "name" => "wwh/position/index",
                            "title" => "查看"
                        ],
                        [
                            "name" => "wwh/position/add",
                            "title" => "添加"
                        ],
                        [
                            "name" => "wwh/position/edit",
                            "title" => "编辑"
                        ],
                        [
                            "name" => "wwh/position/del",
                            "title" => "删除"
                        ]
                    ]
                ],
                [
                    "name" => "wwh/contact",
                    "title" => "联系我们",
                    'icon'  => 'fa fa-smile-o',
                    "ismenu" => 1,
                    'weigh'   => '7',
                    "sublist" => [
                        [
                            "name" => "wwh/contact/index",
                            "title" => "查看"
                        ],
                        [
                            "name" => "wwh/contact/conedit",
                            "title" => "联系我们修改"
                        ]
                    ]
                ]
            ]
        ],
        [
            "name" => "wwh/message",
            "title" => "客户留言",
            'icon'  => 'fa fa-commenting-o',
            "ismenu" => 1,
            'weigh'   => '30',
            "sublist" => [
                [
                    "name" => "wwh/message/index",
                    "title" => "查看"
                ],
                [
                    "name" => "wwh/message/add",
                    "title" => "添加"
                ],
                [
                    "name" => "wwh/message/edit",
                    "title" => "编辑"
                ],
                [
                    "name" => "wwh/message/del",
                    "title" => "删除"
                ]
            ]
        ]
      ]
     ]
    ];
        Menu::create($menu);

		//首次安装创建表并导入测试数据
        \think\addons\Service::importsql('wwh');
        $this->importTestData();
        return true;
    }

	/**
     * 导入测试数据
     */
    protected function importTestData()
    {
        $sqlFile = ADDON_PATH . 'wwh' . DS . 'testdata.sql';
        if (is_file($sqlFile)) {
            $lines = file($sqlFile);
            $templine = '';
            foreach ($lines as $line) {
                if (substr($line, 0, 2) == '--' || $line == '' || substr($line, 0, 2) == '/*') {
                    continue;
                }

                $templine .= $line;
                if (substr(trim($line), -1, 1) == ';') {
                    $templine = str_ireplace('__PREFIX__', config('database.prefix'), $templine);
                    $templine = str_ireplace('INSERT INTO ', 'INSERT IGNORE INTO ', $templine);
                    try {
                        Db::getPdo()->exec($templine);
                    } catch (\Exception $e) {
                        //$e->getMessage();
                    }
                    $templine = '';
                }
            }
        }
        return true;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall()
    {
        Menu::delete("wwh");
        return true;
    }

    /**
     * 插件启用方法
     * @return bool
     */
    public function enable()
    {
        Menu::enable("wwh");
        return true;
    }

    /**
     * 插件禁用方法
     * @return bool
     */
    public function disable()
    {
        Menu::disable("wwh");
        return true;
    }
}
