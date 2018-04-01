<?php
namespace Bybzmt\Blog\Web\Controller\User;

use Bybzmt\Blog\Web\Controller\Web;
use Bybzmt\Blog\Web\Reverse;

class Register extends Web
{
    public $msg;

    public function init()
    {
        $this->msg = $this->getQuery("msg");
    }

    public function show()
    {
        $this->render([
            'msg' => $this->msg,
        ]);
    }
}
