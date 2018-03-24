<?php
namespace Bybzmt\Blog\Common\Table;

use Bybzmt\Blog\Common;
use PDO;

class Tag extends Common\Table
{
    use Common\TableRowCache;

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

    public function getTag(string $name)
    {
        $sql = "select * from {$this->_tableName} where name = ?";

        return $this->query($sql, [$name])->fetch();
    }

}
