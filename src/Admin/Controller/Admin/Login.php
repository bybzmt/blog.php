<?php
namespace Bybzmt\Blog\Admin\Controller\Admin;

use Bybzmt\Blog\Admin\Controller\Web;

class Login extends Web
{
    public $go;

    public function init()
    {
        $this->go = isset($_GET['go']) ? $_GET['go'] : '';
    }

    public function show()
    {
        $this->render(array(
            'go' => $this->go,
        ));
    }

}
