<?php
namespace Bybzmt\Blog\Admin\Controller\Admin;

use Bybzmt\Blog\Admin\Controller\AuthJson;

class RoleAddExec extends AuthJson
{
    private $name;

    public function init()
    {
        $this->name = isset($_POST['name']) ? trim($_POST['name']) : '';
    }

    public function valid()
    {
        if (!$this->name) {
            $this->ret = 1;
            $this->data = "角色名不能为空。";
            return false;
        }

        return true;
    }

    public function exec()
    {
        return $this->_context->getService("Admin")->roleAdd($this->name);
    }


}
