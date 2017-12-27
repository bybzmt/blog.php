<?php
namespace Bybzmt\Blog\Admin\Table;

use Bybzmt\Blog\Common;
use Bybzmt\Blog\Admin;
use PDO;

class Article extends Common\Table\Article
{
    private function buildType(int $type, string $search)
    {
        if ($search) {
            switch ($type) {
            case 1: //用户id
                $sql2 = "select id from users where id = ?";
                $ids = $this->getSlave()->fetchColumnAll($sql, [$search]);
                if (!$ids) { return []; }

                return ['user_id'=>$ids];
            case 2: //用户名
                $sql2 = "select id from users where user link ? limit 1000";
                $ids = $this->getSlave()->fetchColumnAll($sql, [$search]);
                if (!$ids) { return []; }

                return ['user_id'=>$ids];
            case 3: //用户昵称
                $sql2 = "select id from users where nickname link ? limit 1000";
                $ids = $this->getSlave()->fetchColumnAll($sql, [$search]);
                if (!$ids) { return []; }

                return ['user_id'=>$ids];
            case 4: //文章id
                return ['id'=>$search];
            case 5: //文章标题
                return ['title'=>$search];
            }
        }

        //无限制条件
        return [];
    }

    //得到后台列表
    public function getAdminList(int $type, string $search, int $offset, int $length)
    {
        $tmps = $this->buildType($type, $search);
        $vals = [];

        $where = $tmps ? " AND " . $this->buildWhere($tmps, $vals) : "";

        $sql = "select * from articles where status > 0 $where order by id desc LIMIT $offset, $length";
        $sql2 = "select COUNT(*) from articles where status > 0 " . $where;

        $rows = $this->query($sql, $vals)->fetchAll();
        $count = $this->query($sql2, $vals)->fetchColumn();

        return [$rows, $count];
    }
}
