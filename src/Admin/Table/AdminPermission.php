<?php
namespace Bybzmt\Blog\Admin\Table;

use Bybzmt\Blog\Admin;

/**
 * 权限说明
 */
class AdminPermission extends Admin\Table
{
    protected $_dbName = 'blog';
    protected $_tableName = 'admin_permissions';
    protected $_primary = 'permission';
    protected $_columns = [
        'permission',
        'about',
    ];

    //未找到记录仅仅是没有说明而己，并非没有这个权限
    public function get($key)
    {
        $val = parent::get($key);
        if (!$val) {
            $val = $key;
        }
        return $val;
    }

    //未找到记录仅仅是没有说明而己，并非没有这个权限
    public function gets($keys)
    {
        $rows = parent::gets($keys);
        $finds = array_column($rows, $this->_primary);

        $diff = array_diff($keys, $finds);
        foreach($diff as $key) {
            $rows[] = array('permission'=>$key, 'about'=>$key);
        }

        return $rows;
    }

}
