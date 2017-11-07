<?php
namespace Bybzmt\Blog\Service\Domain;

class User extends Base
{
    public $id;
    public $user;
    public $pass;
    public $nickname;
    public $status;

    protected function init($row)
    {
        $this->id = (int)$row['id'];
        $this->user = $row['user'];
        $this->pass = $row['pass'];
        $this->nickname = $row['nickname'];
        $this->status = (int)$row['status'];
    }

    public function getArticleList(int $offset, int $length)
    {
    }

    public function getComments(int $offset, int $length)
    {
    }

    public function loginlog(int $offset, int $length)
    {
    }

    public function getOrders(int $offset, int $length)
    {
    }

    public function getAccounts()
    {
    }

}
