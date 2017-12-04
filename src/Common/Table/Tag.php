<?php
namespace Bybzmt\Blog\Common\Table;

use Bybzmt\Blog\Common;

class Tag extends Common\Table
{
    protected $_dbName = 'blog';
    protected $_tableName = 'tags';
    protected $_primary = 'id';
    protected $_columns = [
        'id',
        'name',
        'sort',
        'status',
        'top',
    ];

    public function getIndexTagIds()
    {
        $sql = "SELECT id FROM tags WHERE top > 0 ORDER BY top ASC";

        return $this->getSlave()->fetchColumnAll($sql);
    }
}
