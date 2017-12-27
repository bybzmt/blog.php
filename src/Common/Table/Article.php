<?php
namespace Bybzmt\Blog\Common\Table;

use Bybzmt\Blog\Common;
use PDO;

class Article extends Common\TableRowCache
{
    protected $_keyPrefix = __CLASS__;
    protected $_dbName = 'blog';
    protected $_tableName = 'articles';
    protected $_primary = 'id';
    protected $_columns = [
        'id',
        'user_id',
        'title',
        'intro',
        'content',
        'addtime',
        'edittime',
        'status',
        'locked',
        'deleted',
        'top',
        'cache_tags',
        'cache_comments_num',
    ];

    public function getIndexIds(int $offset, int $length)
    {
        $sql = "select id from articles where status = 1 order by id desc limit $offset, $length";

        return $this->query($sql)->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function incrCommentsNum(int $id, int $num)
    {
        $sql = "update articles set cache_comments_num = cache_comments_num + ? where id = ?";

        $ok = $this->exec($sql, [$num, $id]);
        if ($ok) {
            $this->updateCache($id, function($row) use ($num) {
                $row['cache_comments_num'] += $num;
                return $row;
            });
        }

        return $ok;
    }

    public function decrCommentsNum(int $id, int $num)
    {
        $sql = "update articles set cache_comments_num = cache_comments_num - ? where id = ?";

        $ok = $this->exec($sql, [$num, $id]);
        if ($ok) {
            $this->updateCache($id, function($row) use ($num) {
                $row['cache_comments_num'] -= $num;
                return $row;
            });
        }
        return $ok;
    }
}
