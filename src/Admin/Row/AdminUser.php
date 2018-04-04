<?php
namespace Bybzmt\Blog\Admin\Row;

use Bybzmt\Framework\Row;

class AdminUser extends Row
{
    public function encryptPass($pass)
    {
        //密码摘要，密钥确定后不可更改
        return hash_hmac('md5', $pass, $this->id.'encryptkey');
    }

    public function setPass($pass)
    {
        $saved = $this->encryptPass($pass);

        $ok = $this->getTable("AdminUser")->update($this->id, array('pass'=>$saved));
        if ($ok) {
            $this->pass = $saved;
        }
        return $ok;
    }

    public function setRoot(bool $bool)
    {
        $ok = $this->getTable("AdminUser")->update($this->id, array('isroot'=>(int)$bool));
        if ($ok) {
            $this->isroot = $bool;
        }
        return $ok;
    }

    public function setNickname($nickname)
    {
        $ok = $this->getTable("AdminUser")->update($this->id, array('nickname'=>$nickname));
        if ($ok) {
            $this->nickname = $nickname;
        }
        return $ok;
    }

    public function del()
    {
        $ok = $this->getTable("AdminUser")->update($this->id, array('status'=>0));
        if ($ok) {
            $this->status = 0;
        }
        return $ok;
    }

    public function auditPass()
    {
        $ok = $this->getTable("AdminUser")->update($this->id, array('status'=>2));
        if ($ok) {
            $this->status = 2;
        }
        return $ok;
    }

    public function validPass($pass)
    {
        return $this->encryptPass($pass) == $this->pass;
    }

    /**
     * 得到用户己有的权限标识
     */
    public function getPermissions()
    {
        $table = $this->getTable('AdminUser');

        $permissions1 = $table->getUserPermissions($this->id);
        $permissions2 = $table->getUserRolesPermissions($this->id);

        return array_unique(array_merge($permissions1, $permissions2));
    }

    public function getUserPermissions()
    {
        return $this->getTable('AdminUser')->getUserPermissions($this->id);
    }

    public function setUserPermissions($permissions)
    {
        return $this->getTable('AdminUser')->setUserPermissions($this->id, $permissions);
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

    public function setRoles($roles)
    {
        $role_ids = array();
        foreach ($roles as $role) {
            $role_ids[] = $role->id;
        }

        return $this->getTable("AdminUser")->setUserRoleIds($this->id, $role_ids);
    }
}
