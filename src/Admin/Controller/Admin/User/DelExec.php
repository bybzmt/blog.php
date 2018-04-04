<?php
namespace Bybzmt\Blog\Admin\Controller\Admin\User;

use Bybzmt\Blog\Admin\Controller\AuthJson;

class DelExec extends AuthJson
{
    public $id;
    public $user;

    public function init()
    {
        $this->id = $this->getPost('id');
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

        if ($this->user->isroot) {
            $this->ret = 1;
            $this->data = "不能删除系统管理员！";
            return false;
        }

        return true;
    }

    public function exec()
    {
        return $this->user->del();
    }


}
