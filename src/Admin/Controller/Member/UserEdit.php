<?php
namespace Bybzmt\Blog\Admin\Controller;

use Bybzmt\Blog\Admin;
use Bybzmt\Blog\Admin\Helper\Permissions;

class Member_UserEdit extends AuthWeb
{
    public $user_id;
    public $user;

    public function init()
    {
        $this->user_id = isset($_GET['id']) ? $_GET['id'] : 0;
    }

    public function valid()
    {
        $this->user = $this->_context->getRow('User', $this->user_id);

        if (!$this->user) {
            return false;
        }

        return true;
    }

    public function show()
    {
        $data = array(
            'sidebarMenu' => '会员管理',
            'user' => $this->user,
        );
        $this->render($data);
    }


}
