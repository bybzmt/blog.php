<?php
namespace Bybzmt\Blog\Web\Controller\User;

use Bybzmt\Blog\Web\Controller\Web;
use Bybzmt\Blog\Web\Reverse;

class Logout extends Web
{
    public function exec()
    {
        $this->getHelper("Session")->destroy();

        $url = $this->getHelper("Utils")->mkUrl("Article.Lists");

        header("Location: {$url}");
    }
}
