<?php
namespace Bybzmt\Blog\Admin\Controller\Blog;

use Bybzmt\Blog\Admin\Controller\AuthJson;

class CommentAuditExec extends AuthJson
{
    public $id;
    public $flag;

    public $comment;

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

        $this->comment = $this->getRow("Comment", $this->id);

        if (!$this->comment) {
            $this->ret = 1;
            $this->data = "id不存在。";
            return false;
        }

        return true;
    }

    public function exec()
    {
        if ($this->flag) {
            return $this->comment->restore();
        } else {
            return $this->comment->del();
        }
    }


}
