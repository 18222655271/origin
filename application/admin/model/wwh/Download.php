<?php

namespace app\admin\model\wwh;

use think\Model;

class Download extends Model
{
    // 表名
    protected $name = 'wwh_download';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $deleteTime = false;
    // 追加属性
    protected $append = [

    ];

    protected static function init()
    {
        self::afterInsert(function ($row) {
            $pk = $row->getPk();
            $row->getQuery()->where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);
        });
    }
    
    public function downloadcategory()
    {
        return $this->hasone('\app\admin\model\wwh\Downloadcategory', 'id', 'downloadcategoryid')->setEagerlyType(0);
    }
}
