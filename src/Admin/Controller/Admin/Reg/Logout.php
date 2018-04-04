<?php
namespace Bybzmt\Blog\Admin\Controller\Admin\Reg;

use Bybzmt\Blog\Admin\Controller\Web;

class Logout extends Web
{

    public function show()
    {
        $this->getHelper("Session")->destroy();

        $url = $this->getHelper("Utils")->mkUrl('Admin.Reg.Login');
        header("Location: $url");
        echo "Location: $url";
    }


}
