<?php
namespace Bybzmt\Blog\Web\Controller;

use Twig_Loader_Filesystem;
use Twig_Environment;

use Bybzmt\Blog\Web\Reverse;

abstract class AuthWeb extends Web
{
    public function __construct($context)
    {
        parent::__construct($context);

        if (!$this->_uid) {
            $go = $_SERVER['REQUEST_SCHEME'] . '://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

            $url = $this->_ctx->get("Reverse")->mkUrl('User.Login', ['go'=>$go]);
            header("Location: $url");
            echo "Location: $url";
            die;
        }

    }

}
