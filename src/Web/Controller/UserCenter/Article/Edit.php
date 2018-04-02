<?php
namespace Bybzmt\Blog\Web\Controller\UserCenter\Article;

use Bybzmt\Blog\Web\Controller\AuthWeb;

class Edit extends AuthWeb
{
    private $id;
    private $article;

    public function init()
    {
        $this->id = $this->getQuery("id");
    }

    public function valid()
    {
        $this->article = $this->getRow("Article", $this->id);
        if (!$this->article) {
            echo '文章不存在';
            return false;
        }

        if ($this->article->user_id != $this->_uid) {
            echo '不是您的文章';
            return false;
        }

        return true;
    }

    public function show()
    {
        $tags = $this->article->getTags();
        $tags_txt = array();
        foreach ($tags as $tag) {
            $tags_txt[] = $tag->name;
        }
        $tags_txt = implode(", ", $tags_txt);


        $this->render(array(
            'article' => $this->article,
            'tags' => $tags_txt,
        ));
    }

}
