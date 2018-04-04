<?php
namespace Bybzmt\Blog\Admin\Controller\Admin\User;

use Bybzmt\Blog\Admin\Controller\AuthJson;

class EditExec extends AuthJson
{
    public $id;
    public $nickname;
    public $permissions;
    public $role_ids;

    public $roles = array();
    public $user;

    public function init()
    {
        $this->id = $this->getPost('id');
        $this->nickname = trim($this->getPost('nickname'));
        $this->role_ids = (array)$this->getPost('roles');
        $permissions = (array)$this->getPost('permissions');

        //过滤掉不舍法的参数
        $this->permissions = array_intersect($permissions, $this->getHelper("Permissions")->getAll());

        //过滤掉不合法的id
        $rows = $this->getTable("AdminRole")->gets($this->role_ids);
        foreach ($rows as $row) {
            $this->roles[] = $this->initRow("AdminRole", $row);
        }
    }

    public function valid()
    {
        if (!$this->nickname) {
            $this->ret = 1;
            $this->data = "昵称名不能为空。";
            return false;
        }

        $this->user = $this->getRow("AdminUser", $this->id);
        if (!$this->user) {
            $this->ret = 1;
            $this->data = "用户不存在。";
            return false;
        }

        return true;
    }

    public function exec()
    {
        if ($this->nickname != $this->user->nickname) {
            $this->user->setNickname($this->nickname);
        }

        $this->user->setRoles($this->roles);

        $this->user->setUserPermissions($this->permissions);

        return true;
    }

}
