<?php

namespace app\admin\model\wwh;

use think\Model;

class News extends Model
{
    // 表名
    protected $name = 'wwh_news';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;
    // 追加属性
    protected $append = [
        'tjdata_text'
    ];
    public function newscategory()
    {
        return $this->hasone('\app\admin\model\wwh\Newscategory', 'id', 'newscategoryid')->setEagerlyType(0);
    }
    
    public function getTjdataList()
    {
        return ['0' => __('Tjdata 0'), '1' => __('Tjdata 1')];
    }

    public function getTjdataTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['tjdata']) ? $data['tjdata'] : '');
        $list = $this->getTjdataList();
        return isset($list[$value]) ? $list[$value] : '';
    }
}
