<?php
namespace Bybzmt\Blog\Admin\Controller\Blog;

use Bybzmt\Blog\Admin\Controller\AuthWeb;

class CommentList extends AuthWeb
{
    public $tables = array();
    public $table_current;
    public $type;
    public $keyword;

    public $_page;
    public $_offset;
    public $_length = 10;

    public function init()
    {
        $this->_page = $this->getQuery('page');
        $this->table_current = $this->getQuery('table');
        $this->type = $this->getQuery('type');
        $this->keyword = trim($this->getQuery('search'));

        if ($this->_page < 1) {
            $this->_page = 1;
        }
        $this->_offset = ($this->_page-1) * $this->_length;

        if (!in_array($this->type, [1,2,3,4,5,6])) {
            $this->type = 1;
        }

        $this->tables = $this->getService("Blog")->getCommentTables();

        if (!in_array($this->table_current, $this->tables)) {
            $this->table_current = reset($this->tables);
        }
    }

    public function show()
    {
        //查出所有管理组
        list($rows, $count) = $this->getService("Blog")->
            getCommentList($this->table_current, $this->type, $this->keyword, $this->_offset, $this->_length);

        $flag = $this->getService("Blog")->isCommentTable($this->table_current);
        foreach ($rows as $row) {
            if ($flag) {
                $row->pid = $row->article_id.':'.$row->id;
            } else {
                $row->pid = $row->comment_id.':'.$row->id;
            }
        }

        $this->render(array(
            'sidebarMenu' => '评论管理',
            'search_type' => $this->type,
            'search_keyword' => $this->keyword,
            'rows' => $rows,
            'tables' => $this->tables,
            'table_current' => $this->table_current,
            'row_type' => $flag ? 'comment' : 'reply',
            'pagination' => $this->pagination($count),
        ));
    }

    protected function pagination($count)
    {
        return $this->getHelper("Pagination")->style2($count, $this->_length, $this->_page, function($page){
            $params = array();

            $params['tables'] = $this->table_current;

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
