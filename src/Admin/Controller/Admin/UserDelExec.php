<?php
namespace Bybzmt\Blog\Admin\Controller\Admin;

use Bybzmt\Blog\Admin\Controller\AuthJson;

class UserDelExec extends AuthJson
{
    public $id;
    public $user;

    public function init()
    {
        $this->id = isset($_POST['id']) ? $_POST['id'] : 0;
    }

    public function valid()
    {
        if (!$this->id) {
            $this->ret = 1;
            $this->data = "id不能为空。";
            return false;
        }

        $this->user = $this->_ctx->getRow("AdminUser", $this->id);

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
