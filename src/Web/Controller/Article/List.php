<?php
namespace Bybzmt\Blog\Web\Controller;

use Bybzmt\Blog\Web\Controller;
use Bybzmt\Blog\Web\Reverse;
use Bybzmt\Blog\Common\Helper\Pagination;

class Article_List extends Controller
{
    private $tag_id;
    private $tag;
    private $page;
    private $offset;
    private $length = 4;

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
            $this->tag = $this->getRowCache('Tag', $this->tag_id);
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

    public function run()
    {
        //文章列表
        if ($this->tag) {
            $article_rows = $this->tag->getArticleList($this->offset, $this->length);
            $count = $this->tag->getArticleCount();
        } else {
            $article_rows = $this->getService('Article')->getIndexList($this->offset, $this->length);
            $count = $this->getService('Article')->getIndexCount();
        }

        $articles = [];
        foreach ($article_rows as $row) {
            $article_tags = [];
            foreach ($row->getTags() as $tag) {
                $article_tags[] = array(
                    'name'=>$tag->name,
                    'url' => Reverse::mkUrl('Article.List', ['tag'=>$tag->id]),
                );
            }

            $articles[] = [
                'url' => Reverse::mkUrl('Article.Show', ['id'=>$row->id]),
                'title' => $row->title,
                'intro' => $row->intro,
                'addtime' => $row->addtime,
                'author_nickname' => $row->author->nickname,
                'comments_num' => $row->getCommentsNum(),
                'tags' => $article_tags,
            ];
        }


        $pagination = Pagination::style1($count, $this->length, $this->page, function($page){
            $params = $this->tag_id ? ['tag' => $this->tag_id] : [];
            if ($page > 1) {
                $params['page'] = $page;
            }
            return Reverse::mkUrl('Article.List', $params);
        });

        $tag_rows = $this->getService('Article')->getIndexTags();
        $taglist = [];
        foreach ($tag_rows as $row) {
            $taglist[] = array(
                'name' => $row->name,
                'url' => Reverse::mkUrl('Article.List', ['tag'=>$row->id])
            );
        }

        $data = [
            'articles' => $articles,
            'pagination' => $pagination,
            'taglist' => $taglist,
        ];

        $this->render($data);
    }


}
