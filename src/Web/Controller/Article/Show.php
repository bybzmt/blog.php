<?php
namespace Bybzmt\Blog\Web\Controller\Article;

use Bybzmt\Blog\Web\Controller\Web;
use Bybzmt\Blog\Web\Reverse;

class Show extends Web
{
    public $article;
    public $taglist;

    private $id;
    private $msg;

    public function init()
    {
        $this->id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
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
        $this->article = array(
            'title' => $this->article->title,
            'content' => $this->article->content,
            'addtime' => $this->article->addtime,
            'edittime' => $this->article->edittime,
            'comments_num' => $this->article->getCommentsNum(),
            'author_nickname' => $this->article->author->nickname,
        );

        $tag_rows = $this->_context->getService('Article')->getIndexTags();
        $taglist = [];
        foreach ($tag_rows as $row) {
            $this->taglist[] = array(
                'name' => $row->name,
                'url' => Reverse::mkUrl('Article.Lists', ['tag'=>$row->id])
            );
        }

        $this->render();
    }


}
