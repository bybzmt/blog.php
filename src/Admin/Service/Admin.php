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

    public function register($user, $pass, $nickname)
    {
        $data = array(
            'user' => $user,
            'pass' => '',
            'nickname' => $nickname,
            'addtime' => date('Y-m-d H:i:s', time()),
            'user_id' => 0,
            'isroot' => 0,
            'status' => 1,
        );

        $id = $this->getTable("AdminUser")->add($data);
        if (!$id) {
            return false;
        }

        $data['id'] = $id;

        $user = $this->initRow("AdminUser", $data);
        $user->setPass($pass);

        //让首个注册用户成为系统管理员，并自动通过
        if ($id == 1) {
            $user->setRoot(true);
            $user->auditPass();
        }

        return true;
    }

    public function roleAdd($name)
    {
        $data = array(
            'name' => $name,
            'addtime' => date("Y-m-d H:i:s"),
            'status' => 1,
        );

        $id = $this->getTable("AdminRole")->add($data);

        return $id ? true : false;
    }

}
