<?php

return array(
  0 =>
  array(
    'name' => 'receive',
    'title' => '收件邮箱',
    'type' => 'string',
    'content' =>
    array(
    ),
    'value' => '597916383@qq.com',
    'rule' => '',
    'msg' => '',
    'tip' => '用于接收客户留言邮件推送',
    'ok' => '',
    'extend' => '',
  ),
  1 =>
  array(
    'name' => 'prostatus',
    'title' => '产品分类侧栏',
    'type' => 'radio',
    'content' =>
    array(
      1 => '显示',
      0 => '隐藏',
    ),
    'value' => '1',
    'rule' => 'required',
    'msg' => '',
    'tip' => '产品分类侧栏状态',
    'ok' => '',
    'extend' => '',
  ),
  2 =>
  array(
    'name' => 'rewrite',
    'title' => '伪静态',
    'type' => 'array',
    'content' =>
    array(
    ),
    'value' =>
    array(
      'index/index' => '/wwh/$',
      'index/product' => '/wwh/product/[:id]',
      'index/product_detail' => '/wwh/product_detail/[:id]',
      'index/cases' => '/wwh/cases/[:id]',
      'index/cases_detail' => '/wwh/cases_detail/[:id]',
      'index/service' => '/wwh/service/$',
      'index/market' => '/wwh/market/$',
      'index/download' => '/wwh/download/$',
      'index/news' => '/wwh/news/[:id]',
      'index/news_detail' => '/wwh/news_detail/[:id]',
      'index/about' => '/wwh/about/$',
      'index/honor' => '/wwh/honor/$',
      'index/join' => '/wwh/join/$',
      'index/contact' => '/wwh/contact/$',
      'index/search' => '/wwh/search/$',
    ),
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
);
