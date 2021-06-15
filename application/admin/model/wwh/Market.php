<?php

namespace app\admin\model\wwh;

use think\Model;

class Market extends Model
{
    // 表名
    protected $name = 'wwh_market';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'citylist_text'
    ];
    

    
    public function getCitylistList()
    {
        return ['浙江' => __('浙江'), '江苏' => __('江苏'), '安徽' => __('安徽'), '山东' => __('山东'), '福建' => __('福建'), '广东' => __('广东'), '江西' => __('江西'), '北京' => __('北京'), '陕西' => __('陕西'), '河北' => __('河北'), '辽宁' => __('辽宁'), '湖南' => __('湖南'), '河南' => __('河南'), '上海' => __('上海'), '云南' => __('云南'), '四川' => __('四川'), '湖北' => __('湖北'), '吉林' => __('吉林'), '山西' => __('山西'), '重庆' => __('重庆'), '广西' => __('广西'), '天津' => __('天津'), '内蒙古' => __('内蒙古'), '贵州' => __('贵州'), '黑龙江' => __('黑龙江'), '海南' => __('海南'), '台湾' => __('台湾'), '香港' => __('香港'), '新疆' => __('新疆'), '甘肃' => __('甘肃'), '宁夏' => __('宁夏'), '青海' => __('青海'), '澳门' => __('澳门'), '西藏' => __('西藏')];
    }


    public function getCitylistTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['citylist']) ? $data['citylist'] : '');
        $list = $this->getCitylistList();
        return isset($list[$value]) ? $list[$value] : '';
    }
}
