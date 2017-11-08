<?php
namespace Bybzmt\Blog\Common;

use Twig_Loader_Filesystem;
use Twig_Environment;

abstract class Controller
{
    use Loader;

    public function __construct()
    {
        $this->_context = new Context();
    }

    public function execute()
    {
        try {
            $this->init();

            if ($this->valid() && $this->exec()) {
                $this->run();
            } else {
                $this->fail();
            }
        } catch(Exception $e) {
            $this->error($e);
        }
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
    }

    public function error($e)
    {
        throw $e;
    }

    abstract public function run();

    public function render(array $data, string $name=null)
    {
        $class = static::class;
        $idx = strrpos($class, '\\');
        $idx2 = strlen(substr(__NAMESPACE__, 0, strrpos(__NAMESPACE__, '\\')));

        $dir = __DIR__ . '/../'. str_replace('\\', '/', substr($class, $idx2, $idx - $idx2));

        if (!$name) {
            $name = str_replace('_', '/', substr($class, $idx + 1));
        }
        $file = $name . '.tpl';

        $loader = new Twig_Loader_Filesystem($dir);
        $twig = new Twig_Environment($loader, array(
            'cache' => VAR_PATH . '/cache/templates',
            'auto_reload' => true,
        ));

        echo $twig->render($file, $data);
    }

    public function renderJson($data, int $ret=0)
    {
        echo json_encode(array('ret' => $ret, 'data'=> $data), JSON_UNESCAPED_UNICODE);
    }

    public function redirect($url, $code=302)
    {
        header("Location: $url", true, $code);
    }
}
