<?php
namespace Bybzmt\Blog\Admin\Controller\Admin\Role;

use Bybzmt\Blog\Admin\Controller\AuthWeb;

class Lists extends AuthWeb
{
    public function show()
    {
        //查出所有管理组
        $roles = $this->getService("Admin")->getRoles();

        $this->render(array(
            'sidebarMenu' => '角色管理',
            'roles' => $roles,
        ));
    }


}
