<?php
namespace Bybzmt\Blog\Admin\Controller;

class Admin_Logout extends Web
{

    public function show()
    {
        session_start();
        session_destroy();

        $url = Admin\Reverse::mkUrl('Admin.Login');
        header("Location: $url");
        echo "Location: $url";
    }


}
