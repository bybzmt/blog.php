<?php
namespace Bybzmt\Blog\Admin\Controller\Admin;

use Bybzmt\Blog\Admin\Reverse;
use Bybzmt\Blog\Admin\Controller\AuthWeb;

class RoleList extends AuthWeb
{
    public $sidebarMenu = '角色管理';
    public $roles;

    public function show()
    {
        //查出所有管理组
        $this->roles = $this->_context->getService("Admin")->getRoles();

        $this->render();
    }


}
