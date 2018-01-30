<?php
namespace Bybzmt\Blog\Common\Table;

use Bybzmt\Blog\Common;
use PDO;

class SecurityLog extends Common\Table
{
    protected $_dbName = 'blog';
    protected $_tableName = 'security_log';
    protected $_primary = 'id';
    protected $_columns = [
        'id',
        'ip',
        'addtime',
        'type',
    ];

}
