<?php
namespace Bybzmt\Blog\Web\Controller\Article;

use Bybzmt\Blog\Web\Controller\Web;
use Bybzmt\Blog\Web\Reverse;
use Bybzmt\Blog\Web\Helper\Cfg;

class Replys extends Web
{
    public $article_id;
    public $comment_id;
    public $offset;
    public $length = Cfg::REPLY_LENGTH;
    public $page;
    public $msg;

    public $comment;

    public function init()
    {
        $this->article_id = isset($_REQUEST['article']) ? (int)$_REQUEST['article'] : 0;
        $this->comment_id = isset($_REQUEST['comment']) ? (int)$_REQUEST['comment'] : 0;
        $this->page = isset($_REQUEST['page']) ? (int)$_REQUEST['page'] : 1;
        if ($this->page < 1) {
            $this->page = 1;
        }

        $this->offset = ($this->page-1) * $this->length;
    }

    public function valid()
    {
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
        echo $this->msg;
    }

    public function show()
    {
        $rows = $this->comment->getReplys($this->offset, $this->length+1);

        if (count($rows) > $this->length) {
            array_pop($rows);

            $pageNext = $this->page + 1;
        } else {
            $pageNext = false;
        }

        $replys= array();
        foreach ($rows as $reply) {
            $replys[] = array(
                'id' => $reply->id,
                'content' => $reply->content,
                'user' => $this->_context->getLazyRow("User", $reply->user_id),
                'addtime' => $reply->addtime,
            );
        }

        $pagePrev = $this->page - 1;

        $this->render(array(
            'comment_id' => $this->comment_id,
            'replys' => $replys,
            'pagePrev' => $pagePrev,
            'pageNext' => $pageNext,
        ));
    }


}
