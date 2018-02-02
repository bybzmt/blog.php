<?php
namespace Bybzmt\Blog\Admin\Controller\Admin;

use Bybzmt\Blog\Admin\Controller\Web;

class Logout extends Web
{

    public function show()
    {
        session_destroy();

        $url = Admin\Reverse::mkUrl('Admin.Login');
        header("Location: $url");
        echo "Location: $url";
    }


}
