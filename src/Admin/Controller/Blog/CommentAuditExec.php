<?php
namespace Bybzmt\Blog\Admin\Controller\Blog;

use Bybzmt\Blog\Admin\Controller\AuthJson;

class CommentAuditExec extends AuthJson
{
    public $id;
    public $flag;

    public $obj;

    public function init()
    {
        $this->id = $this->getPost('id');
        $this->flag = $this->getPost('flag');
        $this->type = $this->getPost('type');
    }

    public function valid()
    {
        if (!$this->id) {
            $this->ret = 1;
            $this->data = "id不能为空。";
            return false;
        }

        if (!in_array($this->type, ['comment', 'reply'])) {
            $this->ret = 1;
            $this->data = "类型错误";
            return false;
        }

        if ($this->type == 'comment') {
            $this->obj = $this->getRow("Comment", $this->id);
        } else {
            $this->obj = $this->getRow("Reply", $this->id);
        }

        if (!$this->obj) {
            $this->ret = 1;
            $this->data = "id不存在。";
            return false;
        }

        return true;
    }

    public function exec()
    {
        if ($this->flag) {
            return $this->obj->restore();
        } else {
            return $this->obj->del();
        }
    }


}
