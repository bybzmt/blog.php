<?php
namespace Bybzmt\Blog\Admin\Table;

use Bybzmt\Framework\Table;
use PDO;

class AdminUser extends Table
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

    public function getUserList(int $type, string $search, int $offset, int $length)
    {
        $sql = "select * from admin_users where status > 0";

        if ($search) {
            switch ($type) {
            case 1: $sql .= " AND id = ?"; break;
            case 2: $sql .= " AND user LIKE ?"; break;
            case 3: $sql .= " AND nickname LIKE ?"; break;
            default: return [];
            }

            $vals = [$search];
        } else {
            $vals = [];
        }

        $sql .= " LIMIT $offset, $length";

        return $this->query($sql, $vals)->fetchAll();
    }

    public function getUserListCount(int $type, string $search)
    {
        $sql = "select COUNT(*) from admin_users where status > 0";

        if ($search) {
            switch ($type) {
            case 1: $sql .= " AND id = ?"; break;
            case 2: $sql .= " AND user LIKE ?"; break;
            case 3: $sql .= " AND nickname LIKE ?"; break;
            default: return [];
            }

            $vals = [$search];
        } else {
            $vals = [];
        }

        return $this->query($sql, $vals)->fetchColumn();
    }

    public function getUserRoleIds($admin_id)
    {
        $sql = "SELECT role_id FROM `admin_user_roles` where admin_id = ?";

        return $this->query($sql, [$admin_id])->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function setUserRoleIds($admin_id, $role_ids)
    {
        $sql = "delete from admin_user_roles where admin_id = ?";
        $this->exec($sql, [$admin_id]);

        $db = $this->getDB(true);

        $sql = "insert admin_user_roles (admin_id, role_id) values";

        $data = array();
        foreach ($role_ids as $role_id) {
            $data[] = "(". $db->quote($admin_id). "," . $db->quote($role_id) . ")";
        }
        $sql .= implode(",", $data);

        return $db->exec($sql);
    }

    public function findByUser($user)
    {
        $sql = "select `".implode("`,`", $this->_columns)."` from {$this->_tableName} where user = ?";

        return $this->query($sql, [$user])->fetch();
    }

    //用户角色所有的权限
    public function getUserRolesPermissions($id)
    {
        $sql = "SELECT DISTINCT C.permission FROM admin_users AS A
            left join admin_user_roles AS B ON(A.id = B.admin_id)
            left join admin_role_permissions AS C ON(B.role_id = C.role_id)
            where A.id = ?";

        return $this->query($sql, [$id])->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    //用户自身特殊权限
    public function getUserPermissions($id)
    {
        $sql = "select permission from admin_user_permissions where admin_id = ?";

        return $this->query($sql, [$id])->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    //设置用户自身的权限
    public function setUserPermissions($id, array $permissions)
    {
        $sql = "delete from admin_user_permissions where admin_id = ?";
        $this->exec($sql, [$id]);

        if (!$permissions) {
            return;
        }

        $db = $this->getDB(true);

        $sql = "insert admin_user_permissions (admin_id, permission) values";

        $data = array();
        foreach ($permissions as $permission) {
            $data[] = "(". $db->quote($id). "," . $db->quote($permission) . ")";
        }
        $sql .= implode(",", $data);

        return $db->exec($sql);
    }
}
