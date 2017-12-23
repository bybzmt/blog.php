<?php
namespace Bybzmt\Blog\Admin\Controller;

use Bybzmt\Blog\Common\Helper\Pagination;
use Bybzmt\Blog\Admin\Reverse;

class Blog_ArticleEdit extends AuthWeb
{
    public $id;
    public $article;

    public $offset;
    public $length = 10;

    public function init()
    {
        $this->id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
    }

    public function valid()
    {
        $this->article = $this->_context->getRow("Article", $this->id);

        if (!$this->article) {
            return false;
        }

        return true;
    }

    public function show()
    {
        $data = [
            'sidebarMenu' => '文章管理',
            'article' => $this->article,
        ];

        $this->render($data);
    }


}
