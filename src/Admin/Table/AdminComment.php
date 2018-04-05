<?php
namespace Bybzmt\Blog\Admin\Table;

use Bybzmt\Framework\Table;
use PDO;

class AdminComment extends Table
{
    protected $_dbName = 'blog';

    public $commentTableNum = 3;
    public $commentTablePrefix = 'article_comments_';
    public $replyTableNum = 3;
    public $replyTablePrefix = 'article_replys_';

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

    //得到后台评论列表
    public function getList(string $table, int $type, string $search, int $offset, int $length)
    {
        $condition = $this->buildType($type, $search);
        $condition['status'] = 1;

        $vals = [];
        $where = $this->getHelper("SQLBuilder")->where($condition, $vals);

        $sql = "select * from $table where $where LIMIT $offset, $length";
        $sql2 = "select COUNT(*) from $table where $where";

        $rows = $this->query($sql, $vals)->fetchAll();
        $count = $this->query($sql2, $vals)->fetchColumn();

        return [$rows, $count];
    }
}
