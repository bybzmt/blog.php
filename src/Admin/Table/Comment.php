<?php
namespace Bybzmt\Blog\Admin\Table;

use Bybzmt\Blog\Common;
use Bybzmt\Blog\Admin;
use PDO;

class Comment extends Common\Table\Comment
{
    private function buildType(int $type, string $search)
    {
        if ($search) {
            switch ($type) {
            case 1: //用户id
                $sql = "select id from users where id = ?";
                $ids = $this->query($sql, [$search])->fetchAll(PDO::FETCH_COLUMN, 0);
                if (!$ids) { return $empty; }

                return ['user_id'=>$ids];
            case 2: //用户名
                $sql = "select id from users where user like ? limit 1000";
                $ids = $this->query($sql, [$search])->fetchAll(PDO::FETCH_COLUMN, 0);
                if (!$ids) { return $empty; }

                return ['user_id'=>$ids];
            case 3: //用户昵称
                $sql = "select id from users where nickname like ? limit 1000";
                $ids = $this->query($sql, [$search])->fetchAll(PDO::FETCH_COLUMN, 0);
                if (!$ids) { return $empty; }

                return ['user_id'=>$ids];
            case 4: //文章id
                return ['article_id'=>$search];
            case 5: //文章标题
                $sql = "select id from articles where title like ? limit 1000";
                $ids = $this->query($sql, [$search])->fetchAll(PDO::FETCH_COLUMN, 0);
                if (!$ids) { return $empty; }

                return ['user_id'=>$ids];
            case 6: //评论内容
                return ['content'=>$search];
            }
        }

        //无限制条件
        return [];
    }

    //得到后台列表
    public function getAdminList(int $type, string $search, int $offset, int $length)
    {
        $tmps = $this->buildType($type, $search);
        $str = [];
        $vals = [];
        foreach ($tmps as $key => $val) {
            if (is_array($val)) {
                $str[] = "`$key` in (?".str_repeat(", ?", count($val)-1).")";
                $vals = array_merge($vals, $val);
            } else {
                $str[] = "`$key` = ?";
                $vals[] = $val;
            }
        }

        $where = $str ? " AND " . implode(" AND ", $str) : "";

        $sql = "select * from article_comments where status > 0 $where LIMIT $offset, $length";
        $sql2 = "select COUNT(*) from article_comments where status > 0 " . $where;

        $rows = $this->query($sql, $vals)->fetchAll();
        $count = $this->query($sql2, $vals)->fetchColumn();

        return [$rows, $count];
    }
}
