<?php
namespace Bybzmt\Blog\Service;

use Bybzmt\Blog\Common;

class User extends Common\Service
{
    public function guestUser(string $nickname)
    {
        $row = [
            'id' => 0,
            'user' => '',
            'pass' => '',
            'nickname' => $nickname,
            'status' => 0,
        ];
        return $this->initRow('User', $row);
    }

    //得到用户
    public function get(int $id)
    {
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
