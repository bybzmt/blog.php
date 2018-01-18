<?php
namespace Bybzmt\Blog\Web\Controller;

use Bybzmt\Blog\Web\Controller\Web;
use Bybzmt\Blog\Web\Reverse;

class Logout extends Web
{
    public function exec()
    {
        session_destroy();

        header("Location: /");
        die;
    }
}
