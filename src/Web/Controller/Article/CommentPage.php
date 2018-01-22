<?php
namespace Bybzmt\Blog\Web\Controller\Article;

use Bybzmt\Blog\Web\Controller\Web;
use Bybzmt\Blog\Web\Reverse;

class CommentPage extends Web
{
    public $article_id;
    public $comment_id;
    public $msg;

    public $comment;

    public function init()
    {
        $this->article_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $this->comment_id = isset($_POST['comment']) ? (int)$_POST['comment'] : 0;
    }

    public function valid()
    {
        $this->article = $this->_context->getRow('Article', $this->article_id);
        if (!$this->article) {
            $this->msg = "文章不存在";
            return false;
        }

        if (!$this->comment_id) {
            $this->msg = "评论不存在";
            return false;
        }

        $this->comment = $this->_context->getRow('Comment', $this->article_id.":".$this->comment_id);
        if (!$this->comment) {
            $this->msg = "被回复的评论不存在";
            return false;
        }

        if ($this->comment->article_id != $this->article_id) {
            $this->msg = "文章id有误";
            return false;
        }

        if ($this->comment->status != 1) {
            $this->msg = "评论己屏蔽";
            return false;
        }

        return true;
    }

    public function fail()
    {
        echo json_encode(['ret'=>1, 'data' => $this->msg], JSON_UNESCAPED_UNICODE);
    }

    public function show()
    {
        if ($this->comment->reply_id == 0) {
            $page = $this->comment->getCurrentPage(10);

            $url = Reverse::mkUrl("Article.Show", ['id'=>$this->article_id, 'page'=>$page]);
        } else {
            $parent = $this->_context->getRow("Comment", $this->comment->comment_id);

            $page = $parent->getCurrentPage(10);

            $page2 = $this->comment->getCurrentPage(10);

            $url = Reverse::mkUrl("Article.Show", ['id'=>$this->article_id, 'page'=>$page]);

            $url .= "#reply=".$this->comment_id."&page=".$page2;
        }

        echo json_encode(['ret'=>0, 'data'=>$url], JSON_UNESCAPED_UNICODE);
    }


}
