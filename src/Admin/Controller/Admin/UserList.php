<?php
namespace Bybzmt\Blog\Admin\Controller;

class Admin_UserList extends AuthWeb
{

    public function show()
    {
        //查出所有管理组
        $users = $this->getService("Admin")->getUserList(0, 10);

        $data = [
            'sidebarMenu' => '管理员管理',
            'users' => $users,
        ];

        $this->render($data);
    }


}
