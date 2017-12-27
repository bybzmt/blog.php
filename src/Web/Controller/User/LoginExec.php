<?php
namespace Bybzmt\Blog\Web\Controller;

use Bybzmt\Blog\Web\Controller\AuthWeb;
use Bybzmt\Blog\Web\Reverse;

class User_Logout extends AuthWeb
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
