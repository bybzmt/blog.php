<?php
namespace Bybzmt\Blog\Web;

use Bybzmt\Blog\Common;
use Bybzmt\Blog\Common\Helper\StaticFile;

class Router extends Common\Router
{
    public function _init()
    {
        $this->get('/', ':Article.Lists');
        $this->get('/list/(\d+)', ':Article.Lists:page');
        $this->get('/tag/(\d+)', ':Article.Lists:tag');
        $this->get('/tag/(\d+)/(\d+)', ':Article.Lists:tag:page');
        $this->get('/article/(\d+)', ':Article.Show:id');
        $this->get('/about', ':About.Hello');
        $this->get('/contact', ':About.Contact');
        $this->get('/article/replys', ':Article.Replys');
        $this->get('/article/redirect', ':Article.Redirect');
        $this->post('/article/comment', ':Article.Comment');
        $this->post('/article/replys', ':Article.Replys');

        $this->get('/user', ':User.Show');
        $this->get('/login', ':User.Login');
        $this->get('/logout', ':User.Logout');
        $this->get('/captcha', ':User.Captcha');
        $this->get('/register', ':User.Register');
        $this->post('/post/register', ':User.RegisterExec');
        $this->post('/post/login', ':User.LoginExec');
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
