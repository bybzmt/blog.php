<?php
namespace Bybzmt\Blog\Web\Controller;

use Bybzmt\Blog\Web\Controller;
use Bybzmt\Blog\Web\Reverse;

class Article_Show extends Controller
{
    private $id;
    private $article;
    private $msg;

    public function init()
    {
        $this->id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    }

    public function valid()
    {
        $this->article = $this->getRowCache('Article', $this->id);
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

    public function run()
    {
        $article = array(
            'title' => $this->article->title,
            'content' => $this->article->content,
            'addtime' => $this->article->addtime,
            'edittime' => $this->article->edittime,
            'comments_num' => $this->article->getCommentsNum(),
            'author_nickname' => $this->article->author->nickname,
        );

        $tag_rows = $this->getService('Article')->getIndexTags();
        $taglist = [];
        foreach ($tag_rows as $row) {
            $taglist[] = array(
                'name' => $row->name,
                'url' => Reverse::mkUrl('Article.List', ['tag'=>$row->id])
            );
        }

        $data = [
            'article' => $article,
            'taglist' => $taglist,
        ];

        $this->render($data);
    }


}
