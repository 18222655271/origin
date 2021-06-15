<?php

namespace app\admin\model\wwh;

use think\Model;

class Honor extends Model
{
    // 表名
    protected $name = 'wwh_honor';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];
}
