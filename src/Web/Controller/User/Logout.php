<?php
namespace Bybzmt\Blog\Web\Controller\User;

use Bybzmt\Blog\Web\Controller\Web;
use Bybzmt\Blog\Web\Reverse;

class Logout extends Web
{
    public function exec()
    {
        $this->_session->destroy();

        $url = $this->_ctx->get("Reverse")->mkUrl("Article.Lists");

        header("Location: {$url}");
    }
}
