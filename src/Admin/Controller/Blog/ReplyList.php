<?php
namespace Bybzmt\Blog\Admin\Controller\Blog;

use Bybzmt\Blog\Admin\Controller\AuthWeb;

class ReplyList extends AuthWeb
{
    public $type;
    public $keyword;

    public $_page;
    public $_offset;
    public $_length = 10;

    public function init()
    {
        $this->_page = $this->getQuery('page');
        $this->type = $this->getQuery('type');
        $this->keyword = trim($this->getQuery('search'));

        if ($this->_page < 1) {
            $this->_page = 1;
        }
        $this->_offset = ($this->_page-1) * $this->_length;

        if (!in_array($this->type, [1,2,3,4,5,6])) {
            $this->type = 1;
        }
    }

    public function show()
    {
        //查出所有管理组
        list($comments, $count) = $this->getService("Blog")->
            getReplyList($this->type, $this->keyword, $this->_offset, $this->_length);

        $this->render(array(
            'sidebarMenu' => '评论管理',
            'search_type' => $this->type,
            'search_keyword' => $this->keyword,
            'comments' => $comments,
            'pagination' => $this->pagination($count),
        ));
    }

    protected function pagination($count)
    {
        return $this->getHelper("Pagination")->style2($count, $this->_length, $this->_page, function($page){
            $params = array();

            if ($this->keyword) {
                $params['type'] = $this->type;
                $params['search'] = $this->keyword;
            }

            if ($page > 1) {
                $params['page'] = $page;
            }

            return $this->getHelper("Utils")->mkUrl('Blog.CommentList', $params);
        });
    }


}
