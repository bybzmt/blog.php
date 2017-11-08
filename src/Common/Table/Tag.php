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
    ];
}
