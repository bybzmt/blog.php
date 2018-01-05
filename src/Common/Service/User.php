<?php
namespace Bybzmt\Blog\Common\Service;

use Bybzmt\Blog\Common;

class User extends Common\Service
{
    //得到用户
    public function getUser(string $username)
    {
        $row = $this->_context->getTable('User')->findByUsername($username);
        if (!$row) {
            return false;
        }

        return $this->_context->initRow("User", $row);
    }

    //添加新用户
    public function add(string $user, string $name)
    {
    }




}
