<?php
namespace Bybzmt\Blog\Admin\Service;

use Bybzmt\Blog\Admin as PAdmin;

class Admin extends PAdmin\Service
{

    public function findUser($user)
    {
        $row = $this->_ctx->getTable('AdminUser')->findByUser($user);
        if (!$row) {
            return false;
        }

        return $this->_ctx->initRow('AdminUser', $row);
    }

    public function getRoles()
    {
        $rows = $this->_ctx->getTable("AdminRole")->getAll();
        $roles = [];

        foreach ($rows as $row) {
            $roles[] = $this->_ctx->initRow("AdminRole", $row);
        }

        return $roles;
    }

    public function getUserList($type, $search, $offset, $length)
    {
        $rows = $this->_ctx->getTable("AdminUser")->getUserList($type, $search, $offset, $length);
        $count = $this->_ctx->getTable("AdminUser")->getUserListCount($type, $search);
        $users = [];

        foreach ($rows as $row) {
            $users[] = $this->_ctx->initRow("AdminUser", $row);
        }

        return [$users, $count];
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

        $id = $this->_ctx->getTable("AdminUser")->add($data);
        if (!$id) {
            return false;
        }

        $data['id'] = $id;

        $user = $this->_ctx->initRow("AdminUser", $data);
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

        $id = $this->_ctx->getTable("AdminRole")->add($data);

        return $id ? true : false;
    }

}
