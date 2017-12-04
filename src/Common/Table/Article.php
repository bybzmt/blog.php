<?php
namespace Bybzmt\Blog\Common\Table;

use Bybzmt\Blog\Common;

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

        return $this->getSlave()->fetchColumnAll($sql);
    }

    public function addCommentsNum($id, $num)
    {
        $sql = "update set articles set cache_comments_num = cache_comments_num + ? where id = ?";

        return $this->getMaster()->execute($sql, [$num, $id]);
    }
}
