<?php

namespace addons\kefu\controller;

use think\addons\Controller;
use think\Request;

class Base extends Controller
{

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $config = get_addon_config('kefu');
        // 设定主题模板目录
        $this->view->engine->config('view_path', $this->view->engine->config('view_path') . trim($config['theme']) . DS);
    }

    protected function _initialize()
    {
        parent::_initialize();
    }

}
