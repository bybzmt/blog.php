<?php
namespace Bybzmt\Blog\Web\Controller;

use Twig_Loader_Filesystem;
use Twig_Environment;

use Bybzmt\Blog\Common;

abstract class Web extends Common\Controller
{
    public function init()
    {
    }

    public function valid()
    {
        return true;
    }

    public function exec()
    {
        return true;
    }

    public function fail()
    {
    }

    public function onException($e)
    {
        throw $e;
    }

    public function show()
    {
    }

    public function render(string $name=null)
    {
        $dir = __DIR__;

        if (!$name) {
            $controller = substr(static::class, strlen(__NAMESPACE__)+1);
            $name = str_replace('_', '/', $controller);
        }
        $file = $name . '.tpl';

        $loader = new Twig_Loader_Filesystem($dir);
        $twig = new Twig_Environment($loader, array(
            'cache' => VAR_PATH . '/cache/templates',
            'debug' => true,
            'auto_reload' => true,
            'strict_variables' => true,
        ));

        echo $twig->render($file, array_filter(
            get_object_vars($this),
            function($key){return $key[0] != '_';},
            ARRAY_FILTER_USE_KEY
        ));
    }

}
