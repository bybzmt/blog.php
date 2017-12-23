<?php
namespace Bybzmt\Blog\Admin\Controller;

use Bybzmt\Blog\Admin;
use Bybzmt\Blog\Admin\Helper\Permissions;

class Admin_UserEditExec extends AuthJson
{
    public $id;
    public $nickname;
    public $permissions;
    public $role_ids;

    public $roles = array();
    public $user;

    public function init()
    {
        $this->id = isset($_POST['id']) ? $_POST['id'] : '';
        $this->nickname = isset($_POST['nickname']) ? trim($_POST['nickname']) : '';
        $this->permissions = isset($_POST['permissions']) ? (array)$_POST['permissions'] : array();
        $this->role_ids = isset($_POST['roles']) ? (array)$_POST['roles'] : array();

        //过滤掉不舍法的参数
        $this->permissions = array_intersect($this->permissions, Permissions::getAll());

        //过滤掉不合法的id
        $rows = $this->_context->getTable("AdminRole")->gets($this->role_ids);
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

        $this->user = $this->_context->getRow("AdminUser", $this->id);
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
