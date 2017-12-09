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
        $this->get('/admin/user/(\d+)', ':Admin.UserShow:id');
        $this->get('/admin/user/edit/(\d+)', ':Admin.UserEdit:id');
        $this->post('/json/admin/user/add', ':Admin.UserAddExec');
        $this->post('/json/admin/user/audit', ':Admin.UserAuditExec');
        $this->post('/json/admin/user/edit', ':Admin.UserEditExec');
        $this->post('/json/admin/user/del', ':Admin.UserDelExec');

        //管理员组管理
        $this->get('/admin/roles', ':Admin.RoleList');
        $this->get('/admin/role/add', ':Admin.RoleAdd');
        $this->get('/admin/role/edit/(\d+)', ':Admin.RoleEdit:id');
        $this->post('/json/admin/role/add', ':Admin.RoleAddExec');
        $this->post('/json/admin/role/edit', ':Admin.RoleEditExec');
        $this->post('/json/admin/role/del', ':Admin.RoleDelExec');

        //用户管理
        $this->get('/users', ':User.List');
        $this->get('/user/add', ':User.Add');
        $this->get('/user/edit/(\d+)', ':User.Edit:id');
        $this->get('/user/del/(\d+)', ':User.Del:id');
        $this->post('/json/user/add', ':User.AddExec');
        $this->post('/json/user/edit', ':User.EditExec');
        $this->post('/json/user/del', ':User.DelExec');
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
