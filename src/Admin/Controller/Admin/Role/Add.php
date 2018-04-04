<?php
namespace Bybzmt\Blog\Admin\Controller\Admin\Role;

use Bybzmt\Blog\Admin\Controller\AuthWeb;

class Add extends AuthWeb
{
    public function show()
    {
        $this->render(array(
            'sidebarMenu' => '角色管理',
        ));
    }


}
