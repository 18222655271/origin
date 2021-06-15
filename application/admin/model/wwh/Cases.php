<?php

namespace app\admin\model\wwh;

use think\Model;

class Cases extends Model
{
    // 表名
    protected $name = 'wwh_cases';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;
    // 追加属性
    protected $append = [
    ];
    public function casescategory()
    {
        return $this->hasone('\app\admin\model\wwh\Casescategory', 'id', 'casescategoryid')->setEagerlyType(0);
    }
}
