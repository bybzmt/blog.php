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
        $this->_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
    }

    public function valid()
    {
        $this->article = $this->_ctx->getRow("Article", $this->_id);

        if (!$this->article) {
            return false;
        }

        return true;
    }

    public function show()
    {
        $this->article->author = $this->_ctx->getRow("User", $this->article->user_id);

        $this->render(array(
            'sidebarMenu' => '文章管理',
            'article' => $this->article,
        ));
    }


}
