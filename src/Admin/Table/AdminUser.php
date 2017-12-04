<?php
namespace Bybzmt\Blog\Admin\Table;

use Bybzmt\Blog\Admin;

class AdminUser extends Admin\Table
{
    protected $_dbName = 'blog';
    protected $_tableName = 'admin_users';
    protected $_primary = 'id';
    protected $_columns = [
        'id',
        'user',
        'pass',
        'nickname',
        'addtime',
        'user_id',
        'isroot',
        'status',
    ];

    public function getAll(int $offset, int $length)
    {
        $sql = "select * from admin_users limit $offset, $length";

        return $this->getSlave()->fetchAll($sql);
    }

    public function getUserRoleIds($admin_id)
    {
        $sql = "SELECT role_id FROM `admin_user_roles` where admin_id = ?";

        return $this->getSlave()->fetchColumnAll($sql);
    }

    public function findByUser($user)
    {
        return $this->getSlave()->find($this->_tableName, $this->_columns, array('user'=>$user));
    }

    public function getUserPermissions($id)
    {
        return $this->getSlave()->findColumnAll($this->_tableName, 'permission', array('admin_id'=>$id));
    }

    public function getUserRolesPermissions($id)
    {
        $sql = "SELECT DISTINCT C.permission FROM admin_users AS A
            left join admin_user_roles AS B ON(A.id = B.admin_id)
            left join admin_role_permissions AS C ON(B.role_id = C.role_id)
            where A.id = ?";

        return $this->getSlave()->fetchColumnAll($sql, array($id));
    }
}
