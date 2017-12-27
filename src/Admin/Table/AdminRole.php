<?php
namespace Bybzmt\Blog\Admin\Table;

use Bybzmt\Blog\Admin;
use PDO;

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

        return $this->query($sql)->fetchAll();
    }

    public function getPermissions($id)
    {
        $sql = "select permission from admin_role_permissions where role_id = ?";

        return $this->query($sql, [$id])->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function setPermissions($id, array $permissions)
    {
        $sql = "delete from admin_role_permissions where role_id = ?";
        $this->exec($sql, [$id]);

        $db = $this->getDB(true);

        $sql = "insert admin_role_permissions (role_id, permission) values";

        $data = array();
        foreach ($permissions as $permission) {
            $data[] = "(". $db->quote($id). "," . $db->quote($permission) . ")";
        }
        $sql .= implode(",", $data);

        return $db->exec($sql);
    }
}
