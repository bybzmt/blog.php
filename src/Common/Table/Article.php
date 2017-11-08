<?php
namespace Bybzmt\Blog\Common\Table;

use Bybzmt\Blog\Common;
use PDO;

class Article extends Common\Table
{
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
        'top',
        'cache_tags',
        'cache_comments_num',
    ];

    public function getIndexIds(int $offset, int $length)
    {
        $sql = "select id from articles where status = 1 order by id desc limit $offset, $length";

        return $this->getSlave()->query($sql)->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getTagListIds(int $tag_id, int $offset, int $length)
    {
        $sql = "select article_id from article_tags where tag_id = ? limit $offset, $length";

        $stmt = $this->getSlave()->prepare($sql);
        $stmt->execute([$tag_id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function addCommentsNum($id, $num)
    {
        $sql = "update set articles set cache_comments_num = cache_comments_num + ? where id = ?";

        $stmt = $this->getMaster()->prepare($sql);
        return $stmt->execute([$num, $id]);
    }
}
