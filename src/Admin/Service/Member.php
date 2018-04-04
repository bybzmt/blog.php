<?php
namespace Bybzmt\Blog\Admin\Service;

use Bybzmt\Framework\Service;

class Member extends Service
{

    public function getUserList($type, $search, $offset, $length)
    {
        $rows = $this->getTable("User")->getList($type, $search, $offset, $length);
        $count = $this->getTable("User")->getListCount($type, $search);
        $users = [];

        foreach ($rows as $row) {
            $users[] = $this->initRow("User", $row);
        }

        return [$users, $count];
    }

}
