<?php
namespace Bybzmt\Blog\Admin\Controller\Admin;

use Bybzmt\Blog\Admin\Controller\AuthJson;
use Bybzmt\Blog\Admin\Helper\Permissions;

class RoleEditExec extends AuthJson
{
    public $id;
    public $name;
    public $permissions;

    public $role;

    public function init()
    {
        $this->id = isset($_POST['id']) ? $_POST['id'] : '';
        $this->name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $this->permissions = isset($_POST['permissions']) ? (array)$_POST['permissions'] : array();

        //过滤掉不舍法的参数
        $this->permissions = array_intersect($this->permissions, Permissions::getAll());
    }

    public function valid()
    {
        if (!$this->name) {
            $this->ret = 1;
            $this->data = "角色名不能为空。";
            return false;
        }

        $this->role = $this->_context->getRow("AdminRole", $this->id);
        if (!$this->role) {
            $this->ret = 1;
            $this->data = "角色组不存在。";
            return false;
        }

        return true;
    }

    public function exec()
    {
        if ($this->name != $this->role->name) {
            $this->role->setName($this->name);
        }

        $this->role->setPermissions($this->permissions);

        return true;
    }


}
