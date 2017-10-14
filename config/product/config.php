<?php
return array(
    //域名
    'host' => [
        'api' => 'blogapi',
        'web' => 'blog',
        'static' => 's.blog',
        'admin' => 'admin.blog',
        'wap' => 'wap.blog',
        'apph5' => 'apph5.blog',
        'backend' => 'backend.blog',
        'partner' => 'partner.blog',
    ],
    //是使用缓存路由
    'routes_cached' => true,
    //日志
    'log' => [
        'apple-pay' => ['syslog', 'php-apple-pay', 'LOG_USER'],
        'alipay' => ['syslog', 'php-alipay', 'LOG_USER'],
        'debug' => ['syslog', 'php-debug', 'LOG_USER'],
    ]
);
