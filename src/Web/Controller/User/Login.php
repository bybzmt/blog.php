<?php
namespace Bybzmt\Blog\Web\Controller\User;

use Bybzmt\Blog\Web\Controller\Web;
use Bybzmt\Blog\Web\Reverse;

class Login extends Web
{
    public $go;
    public $msg;

    public function init()
    {
        $this->go = $this->getQuery("go");
        $this->msg = $this->getQuery("msg");
    }

    public function show()
    {
        $this->getHelper("Security")->incr_doRegister();

        $this->render([
            'go' => $this->go,
            'msg' => $this->msg,
        ]);
    }
}
