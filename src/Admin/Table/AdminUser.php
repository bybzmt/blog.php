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
        $sql = "select * from admin_users where status > 0 limit $offset, $length";

        return $this->getSlave()->fetchAll($sql);
    }

    public function getUserRoleIds($admin_id)
    {
        $sql = "SELECT role_id FROM `admin_user_roles` where admin_id = ?";

        return $this->getSlave()->fetchColumnAll($sql, array($admin_id));
    }

    public function setUserRoleIds($admin_id, $role_ids)
    {
        $this->getMaster()->delete('admin_user_roles', array('admin_id'=>$admin_id));

        $data = array();
        foreach ($role_ids as $role_id) {
            $data[] = array(
                'admin_id' => $admin_id,
                'role_id' => $role_id,
            );
        }

        return $this->getMaster()->inserts('admin_user_roles', $data);
    }

    public function findByUser($user)
    {
        return $this->getSlave()->find($this->_tableName, $this->_columns, array('user'=>$user));
    }

    //用户角色所有的权限
    public function getUserRolesPermissions($id)
    {
        $sql = "SELECT DISTINCT C.permission FROM admin_users AS A
            left join admin_user_roles AS B ON(A.id = B.admin_id)
            left join admin_role_permissions AS C ON(B.role_id = C.role_id)
            where A.id = ?";

        return $this->getSlave()->fetchColumnAll($sql, array($id));
    }

    //用户自身特殊权限
    public function getUserPermissions($id)
    {
        return $this->getSlave()->findColumnAll('admin_user_permissions', 'permission', array('admin_id'=>$id));
    }

    //设置用户自身的权限
    public function setUserPermissions($admin_id, array $permissions)
    {
        $this->getMaster()->delete('admin_user_permissions', array('admin_id'=>$admin_id));

        $data = array();
        foreach ($permissions as $permission) {
            $data[] = array(
                'admin_id' => $admin_id,
                'permission' => $permission,
            );
        }

        return $this->getMaster()->inserts('admin_user_permissions', $data);
    }
}
