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

}
