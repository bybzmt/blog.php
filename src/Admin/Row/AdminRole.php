<?php
namespace Bybzmt\Blog\Admin\Row;

use Bybzmt\Blog\Admin;
use Bybzmt\Blog\Admin\Helper\Permissions;

class AdminRole extends Admin\Row
{
    public function setName($name)
    {
        $ok = $this->_ctx->getTable("AdminRole")->update($this->id, array("name"=>$name));
        if ($ok) {
            $this->name = $name;
        }
        return $ok;
    }

    public function del()
    {
        $ok = $this->_ctx->getTable("AdminRole")->update($this->id, array("status"=>0));
        if ($ok) {
            $this->status = 0;
        }
        return $ok;
    }

    public function setPermissions($permissions)
    {
        return $this->_ctx->getTable("AdminRole")->setPermissions($this->id, $permissions);
    }

    public function getPermissions()
    {
        return $this->_ctx->getTable("AdminRole")->getPermissions($this->id);
    }
}
