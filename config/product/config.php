<?php
return array(
    //域名
    'host' => [
        'Api' => 'blogapi',
        'Web' => 'blog',
        'Static' => 's.blog',
        'Admin' => 'admin.blog',
        'Wap' => 'wap.blog',
        'Apph5' => 'apph5.blog',
        'Backend' => 'backend.blog',
        'Partner' => 'partner.blog',
    ],
    //是使用缓存路由
    'routes_cached' => true,
    //数据库
    'db' => [
        'blog_slave' => [
            ['mysql:host=127.0.0.1;dbname=blog', 'blog', '123456'],
        ],
        'blog_master' => [
            ['mysql:host=127.0.0.1;dbname=blog', 'blog', '123456'],
        ],
    ],
    //日志
    'log' => [
        //抛弃日志
        'default' => ['null'],
        'sql' => ['PHPlog', 'sql'],
        'apple-pay' => ['syslog', 'php-apple-pay', 'LOG_USER'],
        'alipay' => ['syslog', 'php-alipay', 'LOG_USER'],
        'debug' => ['syslog', 'php-debug', 'LOG_USER'],
        //'security' => ['PHPlog', 'security'],
        'security' => ['null'],
    ],
    //缓存配置
    'memcached' => [
        'default' => [
            'persistent_id' => 'test1',
            'servers' => [
                ['localhost', 11211, 100],
            ],
        ],
    ],
    'redis' => [
        'default' => [
            'host'=>'localhost',
            'port'=>'',
            'timeout'=>10,
            'password'=>null
        ],
    ],
    //锁服务
    'locker' => [
        'type' => 'socket',
        'host' => 'localhost',
        'port' => 80,
        'timeout' => 10,
    ],
);
