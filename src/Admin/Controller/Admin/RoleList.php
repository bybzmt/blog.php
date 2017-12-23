<?php
namespace Bybzmt\Blog\Admin\Controller;

use Bybzmt\Blog\Admin\Reverse;

class Admin_RoleList extends AuthWeb
{

    public function show()
    {
        //查出所有管理组
        $roles = $this->_context->getService("Admin")->getRoles();

        $data = [
            'sidebarMenu' => '角色管理',
            'roles' => $roles,
        ];

        $this->render($data);
    }


}
