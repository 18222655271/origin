<?php

namespace addons\fastexport;

use app\common\library\Menu;
use think\Addons;

/**
 * 插件
 */
class Fastexport extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu = [
            [
                'name'    => 'fastexport',
                'title'   => '数据导出管理',
                'icon'    => 'fa fa-database',
                'remark'  => '上次的文件包和上次执行进度，仅在大量数据导出时，才会记录；普通任务，执行时直接生成Excel文件下载',
                'sublist' => [
                    ['name' => 'fastexport/index', 'title' => '查看'],
                    ['name' => 'fastexport/add', 'title' => '添加'],
                    ['name' => 'fastexport/edit', 'title' => '编辑'],
                    ['name' => 'fastexport/del', 'title' => '删除']
                ]
            ]
        ];
        Menu::create($menu);
        return true;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall()
    {
        Menu::delete('fastexport');
        return true;
    }

    /**
     * 插件启用方法
     * @return bool
     */
    public function enable()
    {
        Menu::enable('fastexport');
        return true;
    }

    /**
     * 插件禁用方法
     * @return bool
     */
    public function disable()
    {
        Menu::disable('fastexport');
        return true;
    }

}
