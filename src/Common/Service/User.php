<?php
namespace Bybzmt\Blog\Common\Service;

use Bybzmt\Blog\Common;

class User extends Common\Service
{
    //得到用户
    public function getUser(int $id)
    {
        return $this->_context->getRow('User', $id);
    }

    //添加新用户
    public function add(string $user, string $name)
    {
    }

    //验证用户密码
    public function validPass(Domain\User $user, string $pass)
    {
    }


}
