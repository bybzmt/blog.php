<?php
namespace Bybzmt\Blog\Web\Controller\UserCenter\Article;

use Bybzmt\Blog\Web\Controller\AuthWeb;
use Bybzmt\Blog\Web\Helper\Cfg;

class Preview extends AuthWeb
{
    public $id;
    public $msg;
    public $article;

    public function init()
    {
        $this->id = (int)$this->getQuery('id');
    }

    public function valid()
    {
        $this->article = $this->getRow('Article', $this->id);
        if (!$this->article) {
            $this->msg = "文章不存在";
            return false;
        }

        if ($this->article->user_id != $this->_uid) {
            $this->msg = "您不能预览别人的文章";
            return false;
        }

        return true;
    }

    public function fail()
    {
        var_dump($this->msg);
    }

    public function show()
    {
        //文章标签列表
        $tags = $this->article->getTags();

        $commentsNum = $this->article->getCommentsNum();

        $author = $this->getRow("User", $this->article->user_id);

        //显示
        $this->render(array(
            'uid' => $this->_uid,
            'taglist' => $tags,
            'article' => $this->article,
            'author' => $author,
            'commentsNum' => $commentsNum,
        ));
    }

}
