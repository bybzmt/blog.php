<?php
namespace Bybzmt\Blog\Admin\Controller\Blog;

use Bybzmt\Blog\Common\Helper\Pagination;
use Bybzmt\Blog\Admin\Reverse;
use Bybzmt\Blog\Admin\Controller\AuthWeb;

class ArticleList extends AuthWeb
{
    public $sidebarMenu = '文章管理';
    public $type;
    public $search;
    public $status;

    public $_page;
    public $_offset;
    public $_length = 10;

    public function init()
    {
        $this->_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $this->search_type = isset($_GET['type']) ? (int)$_GET['type'] : 1;
        $this->search_status = isset($_GET['status']) ? (int)$_GET['status'] : 0;
        $this->search_keyword = isset($_GET['search']) ? trim($_GET['search']) : '';

        if ($this->_page < 1) {
            $this->_page = 1;
        }
        $this->_offset = ($this->_page-1) * $this->_length;

        if (!in_array($this->search_type, [1,2,3,4,5])) {
            $this->search_type = 1;
        }
    }

    public function show()
    {
        //查出所有管理组
        list($this->articles, $count) = $this->_context->getService("Blog")
            ->getArticleList($this->search_type, $this->search_keyword, $this->_offset, $this->_length);

        $this->pagination = Pagination::style1($count, $this->_length, $this->_page, function($page){
            $params = array();

            if ($this->search_keyword) {
                $params['type'] = $this->search_type;
                $params['search'] = $this->search_status;
            }

            if ($page > 1) {
                $params['page'] = $page;
            }

            return Reverse::mkUrl('Blog.ArticleList', $params);
        });

        $this->render();
    }


}
