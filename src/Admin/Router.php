<?php
namespace Bybzmt\Blog\Admin;

use Bybzmt\Blog\Common;
use Bybzmt\Blog\Common\Helper\StaticFile;

class Router extends Common\Router
{
    public function _init()
    {
        $this->get('/', ':Admin.Dashboard');
        $this->get('/login', ':Admin.Login');
        $this->get('/register', ':Admin.Register');
        $this->post('/json/register', ':Admin.RegisterExec');
        $this->post('/json/login', ':Admin.LoginExec');
        $this->get('/logout', ':Admin.Logout');
        $this->get('/captcha', ':Admin.Captcha');

        //管理员管理
        $this->get('/admin/users', ':Admin.UserList');
        $this->get('/admin/user/add', ':Admin.UserAdd');
        $this->get('/admin/user/(\d+)', ':Admin.UserEdit:id');
        $this->post('/json/admin/user/add', ':Admin.UserAddExec');
        $this->post('/json/admin/user/audit', ':Admin.UserAuditExec');
        $this->post('/json/admin/user/edit', ':Admin.UserEditExec');
        $this->post('/json/admin/user/password', ':Admin.UserPasswordExec');
        $this->post('/json/admin/user/del', ':Admin.UserDelExec');

        //管理员组管理
        $this->get('/admin/roles', ':Admin.RoleList');
        $this->get('/admin/role/add', ':Admin.RoleAdd');
        $this->get('/admin/role/(\d+)', ':Admin.RoleEdit:id');
        $this->post('/json/admin/role/add', ':Admin.RoleAddExec');
        $this->post('/json/admin/role/edit', ':Admin.RoleEditExec');
        $this->post('/json/admin/role/del', ':Admin.RoleDelExec');

        //用户管理
        $this->get('/users', ':Member.UserList');
        $this->get('/user/(\d+)', ':Member.UserEdit:id');
        $this->post('/json/user/edit', ':Member.UserEditExec');
        $this->post('/json/user/audit', ':Member.UserAuditExec');

        //博客管理
        $this->get('/blog/articles', ':Blog.ArticleList');
        $this->get('/blog/article/(\d+)', ':Blog.ArticleEdit:id');
        $this->post('/json/blog/article/audit', ':Blog.ArticleAuditExec');

        //博客评论管理
        $this->get('/blog/comments', ':Blog.CommentList');
        $this->get('/blog/comment/(\d+)', ':Blog.CommentEdit:id');
        $this->post('/json/blog/comment/audit', ':Blog.CommentAuditExec');
    }

    protected function default404()
    {
        $file = STATIC_PATH . "/admin" . $this->getUri();

        if (file_exists($file)) {
            StaticFile::readfile($file);
        } else {
            header('HTTP/1.0 404 Not Found');
            echo "Web 404 page not found\n";
        }
    }


}
