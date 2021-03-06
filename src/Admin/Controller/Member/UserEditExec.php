<?php
namespace Bybzmt\Blog\Admin\Controller\Member;

use Bybzmt\Blog\Admin\Helper\Permissions;
use Bybzmt\Blog\Admin\Controller\AuthJson;

class UserEditExec extends AuthJson
{
    public $id;
    public $nickname;

    public $roles = array();
    public $user;

    public function init()
    {
        $this->id = $this->getPost('id');
        $this->nickname = trim($this->getPost('nickname'));
    }

    public function valid()
    {
        if (!$this->nickname) {
            $this->ret = 1;
            $this->data = "昵称名不能为空。";
            return false;
        }

        $this->user = $this->getRow("User", $this->id);
        if (!$this->user) {
            $this->ret = 1;
            $this->data = "用户不存在。";
            return false;
        }

        return true;
    }

    public function exec()
    {
        if ($this->nickname != $this->user->nickname) {
            $this->user->setNickname($this->nickname);
        }

        return true;
    }

}
