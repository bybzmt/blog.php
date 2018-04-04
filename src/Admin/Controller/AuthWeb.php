<?php
namespace Bybzmt\Blog\Admin\Controller;

use Bybzmt\Blog\Common;
use Bybzmt\Blog\Admin;

abstract class AuthWeb extends Web
{
    public function __construct($context)
    {
        parent::__construct($context);

        //判断是否登陆
        $admin_id = $this->_session->get('admin_id');
        if (!$admin_id) {
            $go = $_SERVER['REQUEST_SCHEME'] . '://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $url = $this->getHelper("Utils")->mkUrl('Admin.Reg.Login', ['go'=>$go]);
            header("Location: $url");
            echo "Location: $url";
            die;
        }

        //判断是否有权限
        $admin_isroot = $this->_session->get('admin_isroot');
        if (!$admin_isroot) {
            $admin_permissions = (array)$this->_session->get('admin_permissions');

            $name = substr(static::class, strlen(__NAMESPACE__) + 1);
            if (!in_array($name, $admin_permissions)) {
                $this->render([], 'PermissionDenied');
                die;
            }
        }
    }


}
