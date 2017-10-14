<?php
namespace Bybzmt\Blog\Web;

use \Bybzmt\Blog\Router as PRouter;

class Router extends PRouter
{
    public function _init()
    {
        $this->get('/', ':Article.List');
        $this->get('/article/(\d+)', ':Article.Show:id');
        $this->get('/tag/(\d+)', ':Article.Tag:id');
    }

    protected function default404()
    {
        header('HTTP/1.0 404 Not Found');
        echo "Web 404 page not found\n";
    }
}
