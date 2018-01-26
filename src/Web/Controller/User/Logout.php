<?php
namespace Bybzmt\Blog\Web\Controller\User;

use Bybzmt\Blog\Web\Controller\Web;
use Bybzmt\Blog\Web\Reverse;

class Logout extends Web
{
    public function exec()
    {
        session_destroy();

        $url = Reverse::mkUrl("Article.Lists");

        header("Location: {$url}");
    }
}
