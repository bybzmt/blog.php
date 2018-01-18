<?php
namespace Bybzmt\Blog\Web\Controller\Article;

use Bybzmt\Blog\Web\Controller\Web;
use Bybzmt\Blog\Web\Reverse;

class Show extends Web
{
    public $article;
    public $offset;
    public $lenght = 10;
    public $id;
    public $msg;

    public function init()
    {
        $this->id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $this->page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($this->page < 1) {
            $this->page = 1;
        }

        $this->offset = ($this->page -1) * $this->lenght;
    }

    public function valid()
    {
        $this->article = $this->_context->getRow('Article', $this->id);
        if ($this->article) {
            return true;
        }

        $this->msg = "文章不存在";

        return false;
    }

    public function fail()
    {
        var_dump($this->msg);
    }

    public function show()
    {
        $comments = $this->article->getComments($this->offset, $this->lenght);
        $commentsNum = $this->article->getCommentsNum();

        $comments_ss = array();
        foreach ($comments as $comment) {
            if (!$comment || !$comment->id) {
                continue;
            }

            $replys = array();

            $comments_ss[] = array(
                'id' => $comment->id,
                'content' => $comment->content,
                'addtime' => $comment->addtime,
                'replys' => $replys,
                'user' => $this->_context->getLazyRow("User", $comment->user_id),
            );
        }

        $author = $this->_context->getRow("User", $this->article->user_id);

        $tag_rows = $this->article->getTags();
        $taglist = array();
        foreach ($tag_rows as $row) {
            $taglist[] = array(
                'name' => $row->name,
                'url' => Reverse::mkUrl('Article.Lists', ['tag'=>$row->id])
            );
        }

        $this->render(array(
            'uid' => $this->_uid,
            'taglist' => $taglist,
            'article' => $this->article,
            'author' => $author,
            'comments' => $comments_ss,
            'commentsNum' => $commentsNum,
        ));
    }


}
