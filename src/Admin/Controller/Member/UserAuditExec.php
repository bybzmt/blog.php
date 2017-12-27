<?php
namespace Bybzmt\Blog\Admin\Controller\Member;

use Bybzmt\Blog\Admin\Controller\AuthJson;

class UserAuditExec extends AuthJson
{
    public $id;
    public $flag;

    public $user;

    public function init()
    {
        $this->id = isset($_POST['id']) ? $_POST['id'] : 0;
        $this->flag = isset($_POST['flag']) ? $_POST['flag'] : 0;
    }

    public function valid()
    {
        if (!$this->id) {
            $this->ret = 1;
            $this->data = "id不能为空。";
            return false;
        }

        $this->user = $this->_context->getRow("User", $this->id);

        if (!$this->user) {
            $this->ret = 1;
            $this->data = "id不存在。";
            return false;
        }

        return true;
    }

    public function exec()
    {
        if ($this->flag) {
            return $this->user->enable();
        } else {
            return $this->user->disable();
        }
    }


}
