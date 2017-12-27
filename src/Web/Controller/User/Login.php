<?php
namespace Bybzmt\Blog\Web\Controller\User;

use Bybzmt\Blog\Web\Controller\Web;
use Bybzmt\Blog\Web\Reverse;

class Logout extends AuthWeb
{
    public function exec()
    {
        session_destroy();

        $go = $_SERVER['REQUEST_SCHEME'] . '://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header("Location: $url");
        echo "Location: $url";
        die;
    }
}
