<?php

namespace app\admin\model;

use think\Model;


class FastExport extends Model
{


    // 表名
    protected $name = 'fastexport';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];

    protected $type = [
        'field_config' => 'array',
        'join_table'   => 'array',
        'where_field'  => 'array',
        'subtask'      => 'array'
    ];

    public function admin()
    {
        return $this->belongsTo('Admin', 'admin_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
