<?php
namespace Bybzmt\Blog\Web\Controller\Article;

use Bybzmt\Blog\Web\Controller\Web;
use Bybzmt\Blog\Web\Reverse;

class Comment extends Web
{
    public $id;
    public $reply_id;
    public $content;
    public $msg;

    public $user;
    public $article;
    public $reply;

    public function init()
    {
        $this->id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $this->reply_id = isset($_POST['reply']) ? (int)$_POST['reply'] : 0;
        $this->content = isset($_POST['content']) ? trim($_POST['content']) : '';
    }

    public function valid()
    {
        if (!$this->_uid) {
            $this->msg = "请先登陆";
            return false;
        }

        $this->user = $this->_context->getRow("User", $this->_uid);
        if (!$this->user) {
            $this->msg = "请先登陆";
            return false;
        }

        $this->article = $this->_context->getRow('Article', $this->id);
        if (!$this->article) {
            $this->msg = "文章不存在";
            return false;
        }

        if (!$this->content) {
            $this->msg = "评论内容不能为空";
            return false;
        }

        if ($this->reply_id) {
            $this->reply = $this->_context->getRow('Comment', $this->reply_id);
            if (!$this->reply) {
                $this->msg = "被回复的评论不存在";
                return false;
            }
        }

        return true;
    }

    public function exec()
    {
        if ($this->reply) {
            $ok = $this->reply->addReply($this->user, $this->content);
        } else {
            $ok = $this->article->addComment($this->user, $this->content);
        }

        if (!$ok) {
            $this->msg = "操作失败";
            return false;
        }

        return true;
    }

    public function fail()
    {
        echo json_encode(['error'=>1, 'data' => $this->msg], JSON_UNESCAPED_UNICODE);
    }

    public function show()
    {
        echo json_encode(['error'=>0, 'data' => '成功'], JSON_UNESCAPED_UNICODE);
    }


}
