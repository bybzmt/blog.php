<?php
namespace Bybzmt\Blog\Admin\Controller;

use Bybzmt\Blog\Admin;

class Admin_RoleAdd extends AuthWeb
{

    public function show()
    {
        $data = array(
            'sidebarMenu' => '角色管理',
        );
        $this->render($data);
    }


}
