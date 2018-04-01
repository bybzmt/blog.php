<?php
namespace Bybzmt\Blog\Web\Controller\Article;

use Bybzmt\Blog\Web\Controller\Web;
use Bybzmt\Blog\Web\Reverse;
use Bybzmt\Blog\Web\Helper\Cfg;

class Lists extends Web
{
    public $taglist = [];
    public $pagination;
    public $articles;

    private $tag_id;
    private $tag;
    private $page;
    private $offset;
    private $length = Cfg::ARTICLE_LENGTH;

    public function init()
    {
        $page = (int)$this->getQuery('page');
        if ($page < 1) {
            $page = 1;
        }

        $this->tag_id = (int)$this->getQuery('tag');

        $this->page = $page;
        $this->offset = ($page-1) * $this->length;
    }

    public function valid()
    {
        if ($this->tag_id) {
            $this->tag = $this->getRow('Tag', $this->tag_id);
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
            $articles = $this->tag->getArticleList($this->offset, $this->length);
            $count = $this->tag->getArticleCount();
        } else {
            $articles = $this->getService("Article")->getIndexList($this->offset, $this->length);
            $count = $this->getService("Article")->getIndexCount();
        }

        $tags = [];

        $articles = array_filter($articles, function($row) use(&$tags) {
            if (!$row || !$row->id || $row->status == 1) {
                return false;
            }

            $tags = array_merge($tags,$row->getTagsId());

            $row->tags = $row->getTags();

            $row->commentsNum = $row->getCommentsNum();

            $row->author = $this->getLazyRow("User", $row->user_id);
            $row->link = $this->getHelper("Utils")->mkUrl('Article.Show', ['id'=>$row->id]);

            return true;
        });

        $tags = $this->getLazyRows("Tag", array_unique($tags));

        $this->render(array(
            'tag' => $this->tag,
            'articles' => $articles,
            'taglist' => $tags,
            'pagination' => $this->pagination($count),
        ));
    }

    protected function pagination($count)
    {
        return $this->getHelper("Pagination")->style2($count, $this->length, $this->page, function($page){
            $params = array();
            if ($page > 1) {
                $params['page'] = $page;
            }
            if ($this->tag_id) {
                $params['tag'] = $this->tag_id;
            }

            return $this->getHelper("Utils")->mkUrl('Article.Lists', $params);
        });
    }


}
