<?php
namespace Bybzmt\Blog\Admin\Controller\Admin;

use Bybzmt\Blog\Admin\Reverse;
use Bybzmt\Blog\Admin\Controller\AuthWeb;

class RoleList extends AuthWeb
{
    public function show()
    {
        //查出所有管理组
        $roles = $this->_ctx->getService("Admin")->getRoles();

        $this->render(array(
            'sidebarMenu' => '角色管理',
            'roles' => $roles,
        ));
    }


}
