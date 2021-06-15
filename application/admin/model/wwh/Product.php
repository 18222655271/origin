<?php

namespace app\admin\model\wwh;

use think\Model;

class Product extends Model
{
    // 表名
    protected $name = 'wwh_product';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;
    // 追加属性
    protected $append = [
        'tjdata_text'
    ];

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

    public function productcategory()
    {
        return $this->hasone('\app\admin\model\wwh\Productcategory', 'id', 'productcategoryid')->setEagerlyType(0);
    }
	
	protected static function init()
    {
        self::beforeInsert(function ($row) {
            $pids = 0;
            if ($row->productcategoryid != 0) {
                $pids = self::getParentIds($row->productcategoryid);
                $pids .= "," . $row->productcategoryid;
            }
            $row->pids = $pids;
        });
        self::beforeUpdate(function ($row) {
            $changeData = $row->getChangedData();
            if (isset($changeData['productcategoryid'])) {
                $row->pids = self::getParentIds($row->productcategoryid) . ',' . $row->productcategoryid;
            }
        });
    }

    public static function getParentIds($pid) {
        $row = Productcategory::get($pid);
        if ( !$row ) {
            return 0;
        }
        return $row->pids;
    }
}
