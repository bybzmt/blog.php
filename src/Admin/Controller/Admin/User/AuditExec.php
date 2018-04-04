<?php
namespace Bybzmt\Blog\Admin\Controller\Admin\User;

use Bybzmt\Blog\Admin\Controller\AuthJson;

class AuditExec extends AuthJson
{
    public $id;
    public $flag;

    public $user;

    public function init()
    {
        $this->id = $this->getPost('id');
        $this->flag = $this->getPost('flag');
    }

    public function valid()
    {
        if (!$this->id) {
            $this->ret = 1;
            $this->data = "id不能为空。";
            return false;
        }

        $this->user = $this->getRow("AdminUser", $this->id);

        if (!$this->user) {
            $this->ret = 1;
            $this->data = "id不存在。";
            return false;
        }

        if ($this->user->status == 0) {
            $this->ret = 1;
            $this->data = "用户己被删除";
            return false;
        }

        return true;
    }

    public function exec()
    {
        if ($this->flag) {
            return $this->user->auditPass();
        } else {
            return $this->user->del();
        }
    }


}
