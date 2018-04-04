<?php
namespace Bybzmt\Blog\Admin\Controller\Admin\Role;

use Bybzmt\Blog\Admin\Controller\AuthJson;
use Bybzmt\Blog\Admin\Helper\Permissions;

class EditExec extends AuthJson
{
    public $id;
    public $name;
    public $permissions;

    public $role;

    public function init()
    {
        $this->id = $this->getPost('id');
        $this->name = trim($this->getPost('name'));
        $this->permissions = (array)$this->getPost('permissions');

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

        $this->role = $this->getRow("AdminRole", $this->id);
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
