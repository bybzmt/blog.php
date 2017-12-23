<?php
namespace Bybzmt\Blog\Admin\Controller;

use Bybzmt\Blog\Common\Helper\Pagination;
use Bybzmt\Blog\Admin\Reverse;

class Member_UserList extends AuthWeb
{
    public $page;
    public $type;
    public $search;

    public $offset;
    public $length = 10;

    public function init()
    {
        $this->page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $this->type = isset($_GET['type']) ? (int)$_GET['type'] : 1;
        $this->search = isset($_GET['search']) ? trim($_GET['search']) : '';

        if ($this->page < 1) {
            $this->page = 1;
        }
        $this->offset = ($this->page-1) * $this->length;

        if (!in_array($this->type, [1,2,3])) {
            $this->type = 1;
        }
    }

    public function show()
    {
        //查出所有管理组
        list($users, $count) = $this->_context->getService("Member")->getUserList($this->type, $this->search, $this->offset, $this->length);

        $pagination = Pagination::style1($count, $this->length, $this->page, function($page){
            $params = array();

            if ($this->search) {
                $params['type'] = $this->page;
                $params['search'] = $this->search;
            }

            if ($page > 1) {
                $params['page'] = $page;
            }

            return Reverse::mkUrl('Member.UserList', $params);
        });

        $data = [
            'sidebarMenu' => '会员管理',
            'users' => $users,
            'pagination' => $pagination,
            'search_type' => $this->type,
            'search_keyword' => $this->search,
        ];

        $this->render($data);
    }


}
