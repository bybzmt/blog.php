<?php
/*
 * 开发环境配置
 */
return array_replace_recursive(
    require __DIR__ . '/../product/config.php',
    array(
        //是使用缓存路由
        'routes_cached' => false,
        'log' => [
            //开发环境使用文件日志更方便
            'apple-pay' => new ArrayObject(['file', 'php-apple-pay', VAR_PATH . '/log/'.date('Ymd').'.log']),
            'alipay' => new ArrayObject(['file', 'php-alipay', VAR_PATH . '/log/'.date('Ymd').'.log']),
            'debug' => new ArrayObject(['file', 'php-debug', VAR_PATH . '/log/'.date('Ymd').'.log']),
        ]
    )
);
