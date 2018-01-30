<?php
namespace Bybzmt\Blog\Web\Controller;

use Twig_Loader_Filesystem;
use Twig_Environment;

use Bybzmt\Blog\Common;
use Bybzmt\Blog\Web\Helper\TwigExtension;

abstract class Web extends Common\Controller
{
    protected $_uid;

    public function __construct($context)
    {
        parent::__construct($context);

        session_start();

        $this->_uid = isset($_SESSION['uid']) ? (int)$_SESSION['uid'] : 0;
    }

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
        echo 'fail';
    }

    public function onException($e)
    {
        throw $e;
    }

    public function show()
    {
    }

    public function render(array $data=array(), string $name=null)
    {
        $dir = __DIR__;

        if (!$name) {
            $name = substr(static::class, strlen(__NAMESPACE__)+1);
        }
        $file = $name . '.tpl';

        $loader = new Twig_Loader_Filesystem($dir);
        $twig = new Twig_Environment($loader, array(
            'cache' => VAR_PATH . '/cache/templates',
            'debug' => true,
            'auto_reload' => true,
            'strict_variables' => true,
        ));
        $twig->addExtension(new TwigExtension($twig));

        echo $twig->render($file, $data);
    }

}
