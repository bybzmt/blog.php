<?php
namespace Bybzmt\Blog\Admin\Row;

use Bybzmt\Blog\Admin;
use Bybzmt\Blog\Admin\Helper\Permissions;

class AdminRole extends Admin\Row
{
    public $id;
    public $name;
    public $addtime;
    public $status;

    protected function init(array $row)
    {
        $this->id = (int)$row['id'];
        $this->name = $row['name'];
        $this->addtime = strtotime($row['addtime']);
        $this->status = (int)$row['status'];
    }

    public function setName($name)
    {
        $ok = $this->_context->getTable("AdminRole")->update($this->id, array("name"=>$name));
        if ($ok) {
            $this->name = $name;
        }
        return $ok;
    }

    public function del()
    {
        $ok = $this->_context->getTable("AdminRole")->update($this->id, array("status"=>0));
        if ($ok) {
            $this->status = 0;
        }
        return $ok;
    }

    public function setPermissions($permissions)
    {
        return $this->_context->getTable("AdminRole")->setPermissions($this->id, $permissions);
    }

    public function getPermissions()
    {
        return $this->_context->getTable("AdminRole")->getPermissions($this->id);
    }
}
