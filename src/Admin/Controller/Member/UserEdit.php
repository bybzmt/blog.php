<?php
namespace Bybzmt\Blog\Admin\Controller\Member;

use Bybzmt\Blog\Admin\Helper\Permissions;
use Bybzmt\Blog\Admin\Controller\AuthWeb;

class UserEdit extends AuthWeb
{
    public $user_id;
    public $user;

    public function init()
    {
        $this->user_id = $this->getQuery('id');
    }

    public function valid()
    {
        $this->user = $this->getRow('User', $this->user_id);

        if (!$this->user) {
            return false;
        }

        return true;
    }

    public function show()
    {
        $this->render(array(
            'user' => $this->user,
            'sidebarMenu' => '会员管理',
        ));
    }

}
