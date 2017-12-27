<?php
namespace Bybzmt\Blog\Admin\Table;

use Bybzmt\Blog\Admin;
use Bybzmt\Blog\Common;
use PDO;

/**
 * 权限说明
 */
class AdminPermission extends Common\Table
{
    protected $_dbName = 'blog';
    protected $_tableName = 'admin_permissions';
    protected $_primary = 'permission';
    protected $_columns = [
        'permission',
        'about',
    ];

    public function getAll()
    {
        $sql = "select * from admin_permissions";

        return $this->query($sql)->fetchAll();
    }

}
