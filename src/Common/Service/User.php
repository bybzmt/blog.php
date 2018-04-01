<?php
namespace Bybzmt\Blog\Common\Service;

use Bybzmt\Framework\Service;

class User extends Service
{
    //得到用户
    public function getUser(string $username)
    {
        $row = $this->getTable('User')->findByUsername($username);
        if (!$row) {
            return false;
        }

        return $this->initRow("User", $row);
    }

    //添加新用户
    public function addUser(string $user, string $nickName)
    {
        $data = array(
            'user' => $user,
            'pass' => '',
            'nickname' => $nickName,
            'status' => 1,
        );

        $id = $this->getTable("User")->insert($data);

        if ($id) {
            return $this->getRow("User", $id);
        }

        return $id;
    }




}
