<?php
namespace Bybzmt\Blog\Admin\Controller\Admin\Reg;

use Bybzmt\Blog\Admin\Controller\Web;

class Login extends Web
{
    public $go;

    public function init()
    {
        $this->go = $this->getQuery("go");
    }

    public function show()
    {
        $this->render(array(
            'go' => $this->go,
        ));
    }

}
