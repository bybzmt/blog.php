<?php
namespace Bybzmt\Blog\Admin\Table;

use Bybzmt\Blog\Admin;

class AdminRole extends Admin\Table
{
    protected $_dbName = 'blog';
    protected $_tableName = 'admin_roles';
    protected $_primary = 'id';
    protected $_columns = [
        'id',
        'name',
        'addtime',
        'status',
    ];

    public function getAll()
    {
        $sql = "select * from admin_roles where status=1";

        return $this->getSlave()->fetchAll($sql);
    }

    public function getPermissions($id)
    {
        return $this->getSlave()->findColumnAll('admin_role_permissions', 'permission', array('role_id'=>$id));
    }

    public function setPermissions($id, array $permissions)
    {
        $this->getMaster()->delete('admin_role_permissions', array('role_id'=>$id));

        $data = array();
        foreach ($permissions as $permission) {
            $data[] = array(
                'role_id' => $id,
                'permission' => $permission,
            );
        }

        return $this->getMaster()->inserts('admin_role_permissions', $data);
    }
}
