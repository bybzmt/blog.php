<?php
namespace Bybzmt\Blog;

use Bybzmt\Framework\Config;
use Bybzmt\Framework\Front;

//配置文件目录 (根据当前环境载入不同配置)
define('CONFIG_PATH', __DIR__ . "/config/" . (isset($_ENV['ENVIRONMENT']) ? $_ENV['ENVIRONMENT'] : 'dev'));

//可写文件目录
define('VAR_PATH', __DIR__ . "/var");

//类库目录(非composer兼容库)
define('LIBRARY_PATH', __DIR__ . "/library");

//其它资源目录
define('ASSETS_PATH', __DIR__ . "/assets");

//静态文件目录
define('STATIC_PATH', __DIR__ . "/static");

//composer自动加载
require __DIR__ . '/vendor/autoload.php';

$front = new Front(function($name) {
    switch ($name) {
    //命令行
    case '#CLI' : return new Console\Bootstrap();
    //静态文件
    case Config::get('host.static'): return new StaticFile\Bootstrap();
    //api接口
    case Config::get('host.api'): return new Api\Bootstrap();
    //内部访问
    case Config::get('host.backend'): return new Backend\Bootstrap();
    //合作商接口
    case Config::get('host.partner'): return new Partner\Bootstrap();
    //后台
    case Config::get('host.admin'): return new Admin\Bootstrap();
    //默认
    default: return new Web\Bootstrap();
    }
});

$front->run();
