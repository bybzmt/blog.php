<?php
namespace Bybzmt\Blog\Dao;

class User extends Base
{
    public function get($id)
    {
        return Cache\User::get($id);
    }

    public function gets($id)
    {
        return Cache\User::get($id);
    }

    public function add($user, $pass, $nickname)
    {
        $data = array(
            'user' => $user,
            'pass' => $pass,
            'nickname' => $nickname,
            'status' => 1,
        );

        return Cache\User::add($data);
    }

    public function edit($id, $nickname)
    {
        $data = ['nickname' => $nickname];

        return Cache\User::update($id, $data);
    }

    public function del($id)
    {
        $data = ['status' => 2];

        return Cache\User::update($id, $data);
    }
}
