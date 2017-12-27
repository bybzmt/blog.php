<?php
namespace Bybzmt\Blog\Admin\Controller\Admin;

use Bybzmt\Blog\Admin\Controller\AuthWeb;

class RoleAdd extends AuthWeb
{
    public $sidebarMenu = '角色管理';

    public function show()
    {
        $this->render();
    }


}
