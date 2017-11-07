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

        $this->getSlave()->query($sql)->fetchAll(PDO::FETCH_COLUMN);
    }



}
