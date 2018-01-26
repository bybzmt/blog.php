<?php
namespace Bybzmt\Blog\Web\Controller\User;

use Bybzmt\Blog\Web\Controller\Web;
use Bybzmt\Blog\Web\Reverse;

class Register extends Web
{
    public $msg;

    public function init()
    {
        $this->msg = isset($_GET['msg']) ? $_GET['msg'] : '';
    }

    public function show()
    {
        $this->render([
            'msg' => $this->msg,
        ]);
    }
}
