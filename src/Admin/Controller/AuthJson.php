<?php
namespace Bybzmt\Blog\Admin\Controller;

use Bybzmt\Blog\Common;
use Bybzmt\Blog\Admin;

abstract class AuthJson extends Json
{
    public function __construct($context)
    {
        parent::__construct($context);

        //判断是否登陆
        $admin_id = isset($_SESSION['admin_id']) ? (int)$_SESSION['admin_id'] : 0;
        if (!$admin_id) {
            echo json_encode(array('ret'=>2, 'data'=>'没有登陆'));
            die;
        }

        //判断是否有权限
        $admin_isroot = isset($_SESSION['admin_isroot']) ? $_SESSION['admin_isroot'] : false;
        if (!$admin_isroot) {
            $admin_permissions = isset($_SESSION['admin_permissions']) ? (array)$_SESSION['admin_permissions'] : [];

            $name = substr(static::class, strlen(__NAMESPACE__) + 1);
            if (!in_array($name, $admin_permissions)) {
                echo json_encode(array('ret'=>1, 'data'=>'没有权限'));
                die;
            }
        }
    }


}
