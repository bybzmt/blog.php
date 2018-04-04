<?php
namespace Bybzmt\Blog\Admin\Controller\Blog;

use Bybzmt\Blog\Admin\Controller\AuthJson;

class ArticleAuditExec extends AuthJson
{
    public $id;
    public $type;

    public $article;

    public function init()
    {
        $this->id = $this->getPost('id');
        $this->type = $this->getPost('type');
    }

    public function valid()
    {
        if (!$this->id) {
            $this->ret = 1;
            $this->data = "id不能为空。";
            return false;
        }

        $this->article = $this->getRow("Article", $this->id);

        if (!$this->article) {
            $this->ret = 1;
            $this->data = "id不存在。";
            return false;
        }

        if (!in_array($this->type, [1,2,3,4,5])) {
            $this->ret = 1;
            $this->data = "type错误";
            return false;
        }

        return true;
    }

    public function exec()
    {
        switch($this->type) {
        case 1: return $this->article->publish();
        case 2: return $this->article->hidden();
        case 3: return $this->article->unlock();
        case 4: return $this->article->locked();
        case 5: return $this->article->del();
        }
        return false;
    }


}
