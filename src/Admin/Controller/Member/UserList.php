<?php
namespace Bybzmt\Blog\Admin\Controller\Member;

use Bybzmt\Blog\Admin\Controller\AuthWeb;

class UserList extends AuthWeb
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

        if (!in_array($this->type, [1,2,3])) {
            $this->type = 1;
        }
    }

    public function show()
    {
        //查出所有管理组
        list($users, $count) = $this->getService("Member")
            ->getUserList($this->type, $this->keyword, $this->_offset, $this->_length);

        $this->render(array(
            'pagination' => $this->pagination($count),
            'users' => $users,
            'sidebarMenu' => '会员管理',
            'search_type' => $this->type,
            'search_keyword' => $this->keyword,
        ));
    }

    protected function pagination($count)
    {
        return $this->getHelper("Pagination")->style1($count, $this->_length, $this->_page, function($page){
            $params = array();

            if ($this->keyword) {
                $params['type'] = $this->type;
                $params['search'] = $this->keyword;
            }

            if ($page > 1) {
                $params['page'] = $page;
            }

            return $this->getHelper("Utils")->mkUrl('Member.UserList', $params);
        });
    }


}
