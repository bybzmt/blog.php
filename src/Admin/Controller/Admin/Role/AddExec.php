<?php
namespace Bybzmt\Blog\Admin\Controller\Admin\Role;

use Bybzmt\Blog\Admin\Controller\AuthJson;

class AddExec extends AuthJson
{
    private $name;

    public function init()
    {
        $this->name = trim($this->getPost('name'));
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
        return $this->getService("Admin")->roleAdd($this->name);
    }


}
