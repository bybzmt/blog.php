<?php
namespace Bybzmt\Blog\Admin\Row;

use Bybzmt\Blog\Admin;

class AdminUser extends Admin\Row
{
    public $id;
    public $user;
    public $pass;
    public $nickname;
    public $addtime;
    public $user_id;
    public $isroot;
    public $status;

    protected function init(array $row)
    {
        $this->id = (int)$row['id'];
        $this->user = $row['user'];
        $this->pass = $row['pass'];
        $this->nickname = $row['nickname'];
        $this->addtime = strtotime($row['addtime']);
        $this->user_id = (int)$row['user_id'];
        $this->isroot = (bool)$row['isroot'];
        $this->status = (int)$row['status'];
    }

    public function encryptPass($pass)
    {
        //密码摘要，密钥确定后不可更改
        return hash_hmac('md5', $pass, $this->id.'encryptkey');
    }

    public function setPass($pass)
    {
    }

    public function validPass($pass)
    {
        return true;
        return $this->encryptPass($pass) == $this->pass;
    }

    public function getPermissions()
    {
        $table = $this->getTable('AdminUser');

        $permissions1 = $table->getUserPermissions($this->id);

        $permissions2 = $table->getUserRolesPermissions($this->id);

        $rows = array_unique(array_merge($permissions1, $permissions2));

        $permissions = array();
        foreach ($rows as $permission) {
            $permissions[] = $this->getLazyRow("AdminPermission", $permission);
        }

        return $permissions;
    }

    public function getUserPermissions()
    {
        $rows = $this->getTable('AdminUser')->getUserPermissions($this->id);

        $permissions = array();
        foreach ($rows as $permission) {
            $permissions[] = $this->getLazyRow("AdminPermission", $permission);
        }

        return $permissions;
    }

    public function getRoles()
    {
        $table = $this->getTable("AdminUser");
        $role_ids = $table->getUserRoleIds($this->id);

        $roles = [];
        foreach ($role_ids as $role_id) {
            $roles[] = $this->getLazyRow("AdminRole", $role_id);
        }

        return $roles;
    }
}
