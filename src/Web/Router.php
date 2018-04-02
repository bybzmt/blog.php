<?php
namespace Bybzmt\Blog\Web;

use Bybzmt\Framework\Router as Base;

class Router extends Base
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

        $this->get('/user', ':UserCenter.Records');
        $this->get('/user/articles', ':UserCenter.Article.Lists');
        $this->get('/user/article/(\d+)/preview', ':UserCenter.Article.Preview:id');
        $this->get('/user/article/(\d+)/edit', ':UserCenter.Article.Edit:id');
        $this->get('/user/article/add', ':UserCenter.Article.Add');
        $this->post('/post/user/article/add', ':UserCenter.Article.AddExec');
        $this->post('/post/user/article/edit', ':UserCenter.Article.EditExec');
        $this->post('/post/user/article/action', ':UserCenter.Article.Action');

        $this->get('/login', ':User.Login');
        $this->get('/logout', ':User.Logout');
        $this->get('/captcha', ':User.Captcha');
        $this->get('/register', ':User.Register');
        $this->post('/post/register', ':User.RegisterExec');
        $this->post('/post/login', ':User.LoginExec');
    }

}
