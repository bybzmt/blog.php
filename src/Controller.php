<?php
namespace Bybzmt\Blog;

abstract class Controller
{
    public function execute()
    {
        try {
            $this->init();

            if ($this->valid()) {
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

    public function fail()
    {
    }

    public function error($e)
    {
        throw $e;
    }

    abstract public function run();

    public function render(array $data, string $file=null)
    {
        if (!$file) {
            $file = str_replace('_', '/', strrchr(static::class, "\\"));
        }
        $file .= '.tpl';

        $loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
        $twig = new Twig_Environment($loader, array(
            'cache' => VAR_PATH . '/cache/templates',
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
