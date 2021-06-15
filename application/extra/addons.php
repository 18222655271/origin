<?php

return [
    'autoload' => false,
    'hooks' => [
        'sms_send' => [
            'alisms',
        ],
        'sms_notice' => [
            'alisms',
        ],
        'sms_check' => [
            'alisms',
        ],
        'app_init' => [
            'crontab',
            'kefu',
            'shopro',
        ],
        'action_begin' => [
            'geetest',
        ],
        'config_init' => [
            'geetest',
            'third',
        ],
        'upgrade' => [
            'kefu',
            'shopro',
        ],
        'leesignhook' => [
            'leesign',
        ],
        'testhook' => [
            'ykquest',
        ],
    ],
    'route' => [
        '/leesign$' => 'leesign/index/index',
        '/third$' => 'third/index/index',
        '/third/connect/[:platform]' => 'third/index/connect',
        '/third/callback/[:platform]' => 'third/index/callback',
        '/third/bind/[:platform]' => 'third/index/bind',
        '/third/unbind/[:platform]' => 'third/index/unbind',
        '/wwh/$' => 'wwh/index/index',
        '/wwh/product/[:id]' => 'wwh/index/product',
        '/wwh/product_detail/[:id]' => 'wwh/index/product_detail',
        '/wwh/cases/[:id]' => 'wwh/index/cases',
        '/wwh/cases_detail/[:id]' => 'wwh/index/cases_detail',
        '/wwh/service/$' => 'wwh/index/service',
        '/wwh/market/$' => 'wwh/index/market',
        '/wwh/download/$' => 'wwh/index/download',
        '/wwh/news/[:id]' => 'wwh/index/news',
        '/wwh/news_detail/[:id]' => 'wwh/index/news_detail',
        '/wwh/about/$' => 'wwh/index/about',
        '/wwh/honor/$' => 'wwh/index/honor',
        '/wwh/join/$' => 'wwh/index/join',
        '/wwh/contact/$' => 'wwh/index/contact',
        '/wwh/search/$' => 'wwh/index/search',
        '/ykquest$' => 'ykquest/index/index',
        '/ykquest/detail$' => 'ykquest/index/detail',
        '/ykquest/answer$' => 'ykquest/index/answer',
    ],
    'priority' => [],
];
