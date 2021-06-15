<?php

namespace app\admin\model\wwh;

use think\Model;

class Productcategory extends Model
{
    // 表名
    protected $name = 'wwh_productcategory';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;
    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    // 追加属性
    protected $append = [
    ];
	
	protected static function init()
    {
        self::beforeInsert(function ($row) {
            $pids = 0;
            if ($row->pid != 0) {
                $pids = self::getParentIds($row->pid);
                $pids .= "," . $row->pid;
            }
            $row->pids = $pids;
        });
        self::beforeUpdate(function ($row) {
            $changeData = $row->getChangedData();
            if (isset($changeData['pid'])) {
                $row->pids = self::getParentIds($row->pid) . ',' . $row->pid;
            }
        });
    }

    public static function getParentIds($pid) {
        $row = self::get($pid);
        if ( !$row ) {
            return 0;
        }
        return $row->pids;
    }
}
