<?php
namespace Bybzmt\Blog\Web;

use Bybzmt\Blog\Common;
use Bybzmt\Blog\Common\Helper\StaticFile;

class Router extends Common\Router
{
    public function _init()
    {
        $this->get('/', ':Article.List');
        $this->get('/list/(\d+)', ':Article.List:page');
        $this->get('/tag/(\d+)', ':Article.List:tag');
        $this->get('/tag/(\d+)/(\d+)', ':Article.List:tag:page');
        $this->get('/article/(\d+)', ':Article.Show:id');
        $this->get('/about', ':About.Hello');
        $this->get('/contact', ':About.Contact');
    }

    protected function default404()
    {
        $file = STATIC_PATH .'/web'. $this->getUri();

        if (file_exists($file)) {
            StaticFile::readfile($file);
        } else {
            header('HTTP/1.0 404 Not Found');
            echo "Web 404 page not found\n";
        }
    }


}
