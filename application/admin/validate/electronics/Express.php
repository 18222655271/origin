<?php

namespace app\admin\validate\electronics;

use think\Validate;

class Express extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'express_name|物流快递名称' => 'require',
        'express_code1|快递鸟物流编码' => 'require',
        'name|姓名' => 'require',
        'phone|手机号' => 'require|regex:^1[3456789]\d{9}$',
        'city_id|城市' => 'require|integer',
        'province_id|省份' => 'require|integer',
        'region_id|区域' => 'require|integer',
    ];
    /**
     * 提示消息
     */
    protected $message = [
    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'add' => ['express_name','express_code1','name','phone','city_id','province_id', 'region_id'],
        'edit' => ['']
    ];


}
