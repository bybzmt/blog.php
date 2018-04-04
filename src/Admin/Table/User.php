<?php
namespace Bybzmt\Blog\Admin\Table;

use Bybzmt\Blog\Common\Table\User as Base;

class User extends Base
{
    public function getList(int $type, string $search, int $offset, int $length)
    {
        $sql = "select * from users";

        if ($search) {
            switch ($type) {
            case 1: $sql .= " where id = ?"; break;
            case 2: $sql .= " where user LIKE ?"; break;
            case 3: $sql .= " where nickname LIKE ?"; break;
            default: return [];
            }

            $vals = [$search];
        } else {
            $vals = [];
        }

        $sql .= " LIMIT $offset, $length";

        return $this->query($sql, $vals)->fetchAll();
    }

    public function getListCount(int $type, string $search)
    {
        $sql = "select COUNT(*) from users";

        if ($search) {
            switch ($type) {
            case 1: $sql .= " where id = ?"; break;
            case 1: $sql .= " where user LIKE ?"; break;
            case 1: $sql .= " where nickname LIKE ?"; break;
            default: return [];
            }

            $vals = [$search];
        } else {
            $vals = [];
        }

        return $this->query($sql, $vals)->fetchColumn();
    }

}
