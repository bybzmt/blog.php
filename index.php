<?php
namespace Bybzmt\Blog;

use Bybzmt\Blog\Common\Config;

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

if (PHP_SAPI == 'cli') {
    (new Console\Bootstrap())->run();
} else {
    switch ($_SERVER['HTTP_HOST']) {
    //静态文件
    case Config::get('host.static'): (new StaticFile\Bootstrap())->run(); break;
    //api接口
    case Config::get('host.api'): (new Api\Bootstrap())->run(); break;
    //内部访问
    case Config::get('host.backend'): (new Backend\Bootstrap())->run(); break;
    //合作商接口
    case Config::get('host.partner'): (new Partner\Bootstrap())->run(); break;
    //后台
    case Config::get('host.admin'): (new Admin\Bootstrap())->run(); break;
    //默认
    default: (new Web\Bootstrap())->run(); break;
    }
}
