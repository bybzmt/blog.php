<?php
namespace Bybzmt\Blog\Admin\Controller\Admin;

use Bybzmt\Blog\Admin\Controller\AuthJson;

class RoleDelExec extends AuthJson
{
    public $id;
    public $role;

    public function init()
    {
        $this->id = isset($_POST['id']) ? $_POST['id'] : 0;
    }

    public function valid()
    {
        if (!$this->id) {
            $this->ret = 1;
            $this->data = "id不能为空。";
            return false;
        }

        $this->role = $this->_context->getRow("AdminRole", $this->id);

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
