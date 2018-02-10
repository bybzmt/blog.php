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
        $this->go = isset($_GET['go']) ? $_GET['go'] : '';
        $this->msg = isset($_GET['msg']) ? $_GET['msg'] : '';
    }

    public function show()
    {
        $this->_ctx->getService("Security")->incr_doRegister();

        $this->render([
            'go' => $this->go,
            'msg' => $this->msg,
        ]);
    }
}
