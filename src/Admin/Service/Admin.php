<?php
namespace Bybzmt\Blog\Admin\Service;

use Bybzmt\Blog\Admin as PAdmin;

class Admin extends PAdmin\Service
{

    public function findUser($user)
    {
        $row = $this->getTable('AdminUser')->findByUser($user);
        if (!$row) {
            return false;
        }

        return $this->initRow('AdminUser', $row);
    }

    public function getRoles()
    {
        $rows = $this->getTable("AdminRole")->getAll();
        $roles = [];

        foreach ($rows as $row) {
            $roles[] = $this->initRow("AdminRole", $row);
        }

        return $roles;
    }

    public function getUserList($offset, $length)
    {
        $rows = $this->getTable("AdminUser")->getAll(0, 10);
        $users = [];

        foreach ($rows as $row) {
            $users[] = $this->initRow("AdminUser", $row);
        }

        return $users;
    }



}
