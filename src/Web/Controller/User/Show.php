<?php
namespace Bybzmt\Blog\Web\Controller\User;

use Bybzmt\Blog\Web\Controller\AuthWeb;
use Bybzmt\Blog\Web\Reverse;

class Show extends AuthWeb
{
    private $user;
    private $msg;

    public function init()
    {
    }

    public function valid()
    {
        $this->user = $this->_context->getRow('User', $this->_uid);
        if ($this->user) {
            return true;
        }

        $this->msg = "用户不存在";

        return false;
    }

    public function fail()
    {
        var_dump($this->msg);
    }

    public function show()
    {
        var_dump($this->user);
        die;

        $this->render();
    }


}
