<?php
namespace Bybzmt\Blog\Common;

use Bybzmt\Blog\Utils\Config;

use \Bybzmt\Router\Router as PRouter;

abstract class Router extends PRouter
{
    public function __construct()
    {
        if (Config::get('routes_cached')) {
            $this->_restore();
        } else {
            $this->_init();
        }
    }

    abstract protected function _init();

    protected function _restore()
    {
        $file = ASSETS_PATH . '/compiled/' . str_replace('\\', '_', static::class) . '_routes.php';

        $this->_routes = require $file;
    }

    protected function _parseClass($map)
    {
        static $names;
        if (!$names) {
            //根据子类的命名空间得到子类所在模块的命名空间
            $names = implode('\\', array_slice(explode('\\', static::class), 0, -1));
        }

        $str = str_replace($this->_separator_method, '_', $map);

        $class = $names .'\\Controller\\'. $str;
        $method = 'execute';

        return array($class, $method);
    }

    protected function _loadClass($class)
    {
        if (!class_exists($class, false)) {
            $file = substr($class, strlen(__NAMESPACE__));
            $file = __DIR__ . str_replace(array('\\', '_'), '/', $file) . '.php';

            if (file_exists($file)) {
                require $file;
            }
        }

        return class_exists($class, false);
    }
}
