<?php
namespace Bybzmt\Blog\Admin\Controller;

use Twig_Loader_Filesystem;
use Twig_Environment;
use Bybzmt\Blog\Admin\Helper\TwigExtension;

abstract class Web extends Base
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

    public function show()
    {
    }

    public function onException($e)
    {
        //$e2 = new \Exception($e->getMessage(), $e->getCode(), $e);
        //throw $e;
        echo '<pre>';
        echo ($e);
        die;
    }

    public function render(array $data=array(), string $name=null)
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
        $twig->addExtension(new TwigExtension($this->_ctx, $twig));

        echo $twig->render($file, $data);
    }

}
