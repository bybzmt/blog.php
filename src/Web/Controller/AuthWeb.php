<?php
namespace Bybzmt\Blog\Web\Controller;

use Twig_Loader_Filesystem;
use Twig_Environment;

use Bybzmt\Blog\Common;

abstract class AuthWeb extends Web
{
    protected $_uid;

    public function __construct()
    {
        parent::__construct();

        session_start();

        $this->_uid = isset($_SESSION['uid']) ? $_SESSION['uid'] : 0;

        if (!$this->_uid) {
            $go = $_SERVER['REQUEST_SCHEME'] . '://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

            $url = Admin\Reverse::mkUrl('User.Login', ['go'=>$go]);
            header("Location: $url");
            echo "Location: $url";
            die;
        }

    }

}
