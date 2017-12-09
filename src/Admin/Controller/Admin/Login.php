<?php
namespace Bybzmt\Blog\Admin\Controller;

use Bybzmt\Blog\Admin;

class Admin_Login extends Web
{
    public $go;

    public function init()
    {
        $this->go = isset($_GET['go']) ? $_GET['go'] : '';
    }

    public function show()
    {
        $this->render(['go'=>$this->go]);
    }


}
