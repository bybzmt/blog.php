<?php
namespace Bybzmt\Blog\Admin\Controller\Admin\Role;

use Bybzmt\Blog\Admin\Controller\AuthJson;

class DelExec extends AuthJson
{
    public $id;
    public $role;

    public function init()
    {
        $this->id = $this->getPost('id');
    }

    public function valid()
    {
        if (!$this->id) {
            $this->ret = 1;
            $this->data = "id不能为空。";
            return false;
        }

        $this->role = $this->getRow("AdminRole", $this->id);

        if (!$this->role) {
            $this->ret = 1;
            $this->data = "id不能存在。";
            return false;
        }

        if ($this->role->status == 0) {
            $this->ret = 1;
            $this->data = "角色己被删除";
            return false;
        }

        return true;
    }

    public function exec()
    {
        return $this->role->del();
    }


}
