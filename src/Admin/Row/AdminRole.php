<?php
namespace Bybzmt\Blog\Admin\Row;

use Bybzmt\Framework\Row;

class AdminRole extends Row
{
    public function setName($name)
    {
        $ok = $this->getTable("AdminRole")->update($this->id, array("name"=>$name));
        if ($ok) {
            $this->name = $name;
        }
        return $ok;
    }

    public function del()
    {
        $ok = $this->getTable("AdminRole")->update($this->id, array("status"=>0));
        if ($ok) {
            $this->status = 0;
        }
        return $ok;
    }

    public function setPermissions($permissions)
    {
        return $this->getTable("AdminRole")->setPermissions($this->id, $permissions);
    }

    public function getPermissions()
    {
        return $this->getTable("AdminRole")->getPermissions($this->id);
    }
}
