<?php
namespace Bybzmt\Blog\Admin;

use Bybzmt\Framework\Router as Base;

class Router extends Base
{
    public function _init()
    {
        $this->get('/', ':Admin.Dashboard');
        $this->get('/login', ':Admin.Reg.Login');
        $this->get('/register', ':Admin.Reg.Register');
        $this->post('/json/register', ':Admin.Reg.RegisterExec');
        $this->post('/json/login', ':Admin.Reg.LoginExec');
        $this->get('/logout', ':Admin.Reg.Logout');
        $this->get('/captcha', ':Admin.Reg.Captcha');

        //管理员管理
        $this->get('/admin/users', ':Admin.User.Lists');
        $this->get('/admin/user/add', ':Admin.User.Add');
        $this->get('/admin/user/(\d+)', ':Admin.User.Edit:id');
        $this->post('/json/admin/user/add', ':Admin.User.AddExec');
        $this->post('/json/admin/user/audit', ':Admin.User.AuditExec');
        $this->post('/json/admin/user/edit', ':Admin.User.EditExec');
        $this->post('/json/admin/user/password', ':Admin.User.PasswordExec');
        $this->post('/json/admin/user/del', ':Admin.User.DelExec');

        //管理员组管理
        $this->get('/admin/roles', ':Admin.Role.Lists');
        $this->get('/admin/role/add', ':Admin.Role.Add');
        $this->get('/admin/role/(\d+)', ':Admin.Role.Edit:id');
        $this->post('/json/admin/role/add', ':Admin.Role.AddExec');
        $this->post('/json/admin/role/edit', ':Admin.Role.EditExec');
        $this->post('/json/admin/role/del', ':Admin.Role.DelExec');

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

}
