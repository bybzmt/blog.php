<?php
namespace Bybzmt\Blog;

use Bybzmt\Router\Reverse as PReverse;
use Bybzmt\Blog\Utils\Config;

abstract class Reverse extends PReverse
{
    static protected function init()
    {
        static $ins;
        if (!$ins) {
            $ins = new self(self::initData());
        }

        return $ins;
    }

    static protected function initData()
    {
        if (Config::get('routes_cached')) {
            $router = new Router();
            $tool = new \Bybzmt\Router\Tool($router->getRoutes());
            return $tool->convertReverse();
        } else {
            $file = ASSETS_PATH . '/compiled/' . str_replace('\\', '_', static::class) . '_reverse.php';
            return require $file;
        }
    }

    abstract static public function mkUrl(string $func, array $params=array(), bool $https=false);
}
