<?php
namespace Bybzmt\Blog\Admin\Service;

use Bybzmt\Blog\Admin;

class Member extends Admin\Service
{

    public function getUserList($type, $search, $offset, $length)
    {
        $rows = $this->_ctx->getTable("User")->getList($type, $search, $offset, $length);
        $count = $this->_ctx->getTable("User")->getListCount($type, $search);
        $users = [];

        foreach ($rows as $row) {
            $users[] = $this->_ctx->initRow("User", $row);
        }

        return [$users, $count];
    }

}
