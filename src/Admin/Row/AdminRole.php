<?php
namespace Bybzmt\Blog\Admin\Row;

use Bybzmt\Blog\Admin;

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

    public function getPermissions()
    {
        $rows = $this->getTable("AdminRole")->getPermissions();

        $permissions = array();
        foreach ($rows as $permission) {
            $permissions[] = $this->getLazyRow("AdminPermission", $permission);
        }

        return $permissions;
    }
}
