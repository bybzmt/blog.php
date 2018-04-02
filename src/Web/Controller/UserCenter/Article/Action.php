<?php
namespace Bybzmt\Blog\Web\Controller\UserCenter\Article;

use Bybzmt\Blog\Web\Controller\AuthWeb;
use Bybzmt\Blog\Web\Exception;

class Action extends AuthWeb
{
    public $msg;
    public $article;

    public $id;
    public $cmd;

    public function init()
    {
        $this->id = $this->getPost("id");
        $this->cmd = $this->getPost("cmd");
    }

    public function valid()
    {
        //验证安全情况
        if ($this->getHelper("Security")->isLocked()) {
            $this->error = "操作过于频繁请明天再试!";
            return false;
        }

        $this->article = $this->getRow("Article", $this->id);
        if (!$this->article) {
            $this->msg = "文章不存在";
            return false;
        }

        if ($this->article->user_id != $this->_uid) {
            $this->msg = "不能操作别人的文章";
            return false;
        }

        switch ($this->cmd)
        {
        case 'publish':
        case 'delete':
            if (!in_array($this->article->status, [1,4])) {
                $this->msg = "不能操作";
                return false;
            }
            break;
        case 'cancel':
            if (!in_array($this->article->status, [2,3])) {
                $this->msg = "不能操作";
                return false;
            }
            break;
        default:
            $this->error = "指令不对";
            return false;
        }

        return true;
    }

    public function exec()
    {
        switch ($this->cmd)
        {
        case 'publish':
            if ($this->_uid == 1) {
                $this->article->publish();
            } else {
                $this->article->requestAudit();
            }
            break;
        case 'delete':
            $this->article->del();
            break;
        case 'cancel':
            $this->article->hidden();
            break;
        }

        return true;
    }

    public function fail()
    {
        echo json_encode(['ret'=>1, 'data'=>$this->msg]);
    }

    public function show()
    {
        echo json_encode(['ret'=>0, 'data'=>'操作成功']);
    }

}
