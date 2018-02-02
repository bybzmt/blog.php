<?php
namespace Bybzmt\Blog\Admin\Controller\Admin;

use Bybzmt\Blog\Admin\Controller\AuthWeb;

class RoleAdd extends AuthWeb
{
    public function show()
    {
        $this->render(array(
            'sidebarMenu' => '角色管理',
        ));
    }


}
