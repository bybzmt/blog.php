<?php
namespace Bybzmt\Blog\Admin\Controller;

use Bybzmt\Blog\Common;
use Bybzmt\Blog\Admin;

abstract class AuthWeb extends Web
{
    public function __construct()
    {
        parent::__construct();

        session_start();

        //判断是否登陆
        $admin_id = isset($_SESSION['admin_id']) ? (int)$_SESSION['admin_id'] : 0;
        if (!$admin_id) {
            $go = $_SERVER['REQUEST_SCHEME'] . '://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $url = Admin\Reverse::mkUrl('Admin.Login', ['go'=>$go]);
            header("Location: $url");
            echo "Location: $url";
            die;
        }

        //判断是否有权限
        $admin_isroot = isset($_SESSION['admin_isroot']) ? $_SESSION['admin_isroot'] : false;
        if (!$admin_isroot) {
            $admin_permissions = isset($_SESSION['admin_permissions']) ? (array)$_SESSION['admin_permissions'] : [];

            $name = substr(static::class, strlen(__NAMESPACE__) + 1);
            if (!in_array($name, $admin_permissions)) {
                $this->render([], 'PermissionDenied');
                die;
            }
        }
    }


}
