<?php
namespace Bybzmt\Blog\Admin\Controller\Blog;

use Bybzmt\Blog\Common\Helper\Pagination;
use Bybzmt\Blog\Admin\Reverse;
use Bybzmt\Blog\Admin\Controller\AuthWeb;

class ArticleEdit extends AuthWeb
{
    public $article;

    public $_id;

    public function init()
    {
        $this->_id = $this->getQuery('id');
    }

    public function valid()
    {
        $this->article = $this->getRow("Article", $this->_id);

        if (!$this->article) {
            return false;
        }

        return true;
    }

    public function show()
    {
        $this->article->author = $this->getRow("User", $this->article->user_id);

        $this->render(array(
            'sidebarMenu' => '文章管理',
            'article' => $this->article,
        ));
    }


}
