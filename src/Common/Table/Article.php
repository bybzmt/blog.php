<?php
namespace Bybzmt\Blog\Common\Table;

use Bybzmt\Blog\Common;
use PDO;

class Article extends Common\Table
{
    use Common\TableRowCache;

    protected $_dbName = 'blog';
    protected $_tableName = 'articles';
    protected $_primary = 'id';
    protected $_columns = [
        'id',
        'user_id',
        'title',
        'intro',
        'content',
        'html',
        'addtime',
        'edittime',
        'status',
        'locked',
        'deleted',
        'top',
        '_tags',
        '_comments_num',
    ];

    public function getIndexIds(int $offset, int $length)
    {
        $sql = "select id from articles where status = 3 and deleted = 0 order by id desc limit $offset, $length";

        return $this->query($sql)->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function getUserListIds(int $user_id, int $offset, int $length)
    {
        $sql = "select id from articles where user_id = ? AND deleted = 0 order by id desc limit $offset, $length";

        return $this->query($sql, [$user_id])->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function getUserListCount(int $user_id)
    {
        $sql = "select Count(*) from articles where user_id = ? AND deleted = 0";

        return $this->query($sql, [$user_id])->fetch(PDO::FETCH_COLUMN, 0);
    }

    public function incrCommentsNum(int $id, int $num)
    {
        $sql = "update articles set _comments_num = _comments_num + ? where id = ?";

        $ok = $this->exec($sql, [$num, $id]);
        if ($ok) {
            $this->updateCache($id, function($row) use ($num) {
                $row['_comments_num'] += $num;
                return $row;
            });
        }

        return $ok;
    }

    public function decrCommentsNum(int $id, int $num)
    {
        $sql = "update articles set _comments_num = _comments_num - ? where id = ?";

        $ok = $this->exec($sql, [$num, $id]);
        if ($ok) {
            $this->updateCache($id, function($row) use ($num) {
                $row['_comments_num'] -= $num;
                return $row;
            });
        }
        return $ok;
    }
}
