<?php
namespace Bybzmt\Blog\Web\Controller\Article;

use Bybzmt\Blog\Web\Controller\Web;
use Bybzmt\Blog\Web\Reverse;
use Bybzmt\Blog\Common\Helper\Pagination;

class Lists extends Web
{
    public $taglist = [];
    public $pagination;
    public $articles;

    private $tag_id;
    private $tag;
    private $page;
    private $offset;
    private $length = 5;

    public function init()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) {
            $page = 1;
        }

        $this->tag_id = isset($_GET['tag']) ? (int)$_GET['tag'] : 0;

        $this->page = $page;
        $this->offset = ($page-1) * $this->length;
    }

    public function valid()
    {
        if ($this->tag_id) {
            $this->tag = $this->_context->getRow('Tag', $this->tag_id);
            if (!$this->tag) {
                $this->msg = "tag未定义";
                return false;
            }
        }

        return true;
    }

    public function fail()
    {
        var_dump($this->msg);
    }

    public function show()
    {
        //文章列表
        if ($this->tag) {
            $article_rows = $this->tag->getArticleList($this->offset, $this->length);
            $count = $this->tag->getArticleCount();
        } else {
            $article_rows = $this->_context->getService('Article')->getIndexList($this->offset, $this->length);
            $count = $this->_context->getService('Article')->getIndexCount();
        }

        $articles = [];
        foreach ($article_rows as $row) {
            $articles[] = array(
                'title' => $row->title,
                'intro' => $row->intro,
                'addtime' => $row->addtime,
                'commentsNum' => $row->getCommentsNum(),
                'author' => $this->_context->getRow("User", $row->user_id),
                'link' => Reverse::mkUrl('Article.Show', ['id'=>$row->id]),
            );
        }

        $tag_rows = $this->_context->getService('Article')->getIndexTags();
        $taglist = array();
        foreach ($tag_rows as $row) {
            $taglist[] = array(
                'name' => $row->name,
                'url' => Reverse::mkUrl('Article.Lists', ['tag'=>$row->id])
            );
        }

        $pagination = Pagination::style1($count, $this->length, $this->page, function($page){
            $params = array();
            if ($page > 1) {
                $params['page'] = $page;
            }
            if ($this->tag_id) {
                $params['tag'] = $this->tag_id;
            }

            return Reverse::mkUrl('Article.Lists', $params);
        });

        $this->render(array(
            'tag' => $this->tag,
            'articles' => $articles,
            'taglist' => $taglist,
            'pagination' => $pagination,
        ));
    }


}
