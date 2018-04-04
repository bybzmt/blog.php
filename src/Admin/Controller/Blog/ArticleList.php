<?php
namespace Bybzmt\Blog\Admin\Controller\Blog;

use Bybzmt\Blog\Admin\Controller\AuthWeb;

class ArticleList extends AuthWeb
{
    public $type;
    public $search;
    public $status;

    public $_page;
    public $_offset;
    public $_length = 10;

    public function init()
    {
        $this->_page = $this->getQuery('page');
        $this->type = $this->getQuery('type');
        $this->status = $this->getQuery('status');
        $this->keyword = trim($this->getQuery('search'));

        if ($this->_page < 1) {
            $this->_page = 1;
        }
        $this->_offset = ($this->_page-1) * $this->_length;

        if (!in_array($this->type, [1,2,3,4,5])) {
            $this->type = 1;
        }
    }

    public function show()
    {
        //查出所有管理组
        list($articles, $count) = $this->getService("Blog")
            ->getArticleList($this->type, $this->keyword, $this->_offset, $this->_length);

        array_walk($articles, function($article){
            $article->author = $this->getLazyRow("User", $article->user_id);
        });

        $this->render(array(
            'sidebarMenu' => '文章管理',
            'pagination' => $this->pagination($count),
            'articles' => $articles,
            'search_type' => $this->type,
            'search_status' => $this->status,
            'search_keyword' => $this->keyword,
        ));
    }

    protected function pagination($count)
    {
        return $this->getHelper("Pagination")->style2($count, $this->_length, $this->_page, function($page){
            $params = array();

            if ($this->keyword) {
                $params['type'] = $this->type;
                $params['search'] = $this->status;
            }

            if ($page > 1) {
                $params['page'] = $page;
            }

            return $this->getHelper("Utils")->mkUrl('Blog.ArticleList', $params);
        });
    }


}
