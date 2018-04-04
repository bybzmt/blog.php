<?php
namespace Bybzmt\Blog\Admin\Controller\Admin\Role;

use Bybzmt\Blog\Admin\Controller\AuthWeb;

class Edit extends AuthWeb
{
    public $role_id;
    public $role;

    public function init()
    {
        $this->role_id = $this->getQuery('id');
    }

    public function valid()
    {
        $this->role = $this->getRow('AdminRole', $this->role_id);

        if (!$this->role) {
            return false;
        }

        return true;
    }

    public function show()
    {
        //所有权限
        //当前角色所有权限
        $permissions = $this->role->getPermissions();

        //重新整理下格式
        $_permissions = $this->getHelper("Permissions")->reorganize($permissions);

        $this->render(array(
            'permissions' => $_permissions,
            'sidebarMenu' => '角色管理',
            'role' => $this->role,
        ));
    }


}
