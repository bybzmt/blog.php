<?php
namespace Bybzmt\Blog\Common\Table;

use Bybzmt\Blog\Common;

class User extends Common\Table
{
    protected $_dbName = 'blog';
    protected $_tableName = 'users';
    protected $_primary = 'id';
    protected $_columns = [
        'id',
        'user',
        'pass',
        'nickname',
        'status',
    ];
}
