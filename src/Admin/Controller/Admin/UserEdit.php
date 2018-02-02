<?php
namespace Bybzmt\Blog\Admin\Controller\Admin;

use Bybzmt\Blog\Admin\Controller\AuthWeb;
use Bybzmt\Blog\Admin\Helper\Permissions;

class UserEdit extends AuthWeb
{
    public $user_id;
    public $user;

    public function init()
    {
        $this->user_id = isset($_GET['id']) ? $_GET['id'] : 0;
    }

    public function valid()
    {
        $this->user = $this->_context->getRow('AdminUser', $this->user_id);

        if (!$this->user) {
            return false;
        }

        return true;
    }

    public function show()
    {
        //所有权限
        //当前角色所有权限
        $permissions = $this->user->getUserPermissions();

        $roles = $this->user->getRoles();
        $roles = array_column($roles, null, 'id');

        $role_rows;

        $all_roles = $this->_context->getService("Admin")->getRoles();
        foreach ($all_roles as $role) {
            $role_rows[] = array(
                'id' => $role->id,
                'name' => $role->name,
                'status' => isset($roles[$role->id]),
            );
        }

        //重新整理下格式
        $_permissions = Permissions::reorganize($this->_context->getTable("AdminPermission"), $permissions);

        $this->render(array(
            'sidebarMenu' => '管理员管理',
            'permissions' => $_permissions,
            'roles' => $role_rows,
            'user' => $this->user,
        ));
    }


}
