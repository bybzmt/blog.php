<?php
namespace Bybzmt\Blog\Common;

use PDO;
use Bybzmt\DB\Monitor;

/**
 * 数据库表
 */
abstract class Table
{
    use Loader;

    //数据库名
    protected $_dbName;
    //表名
    protected $_tableName;
    //主键
    protected $_primary;
    //表字段
    protected $_columns;

    public function __construct(Context $context)
    {
        $this->_context = $context;
    }

    /**
     * 按主键查找一行数据
     */
    public function get($id)
    {
        return $this->getSlave()->find($this->_tableName, $this->_columns, array($this->_primary=>$id));
    }

    /**
     * 按主键查找一批数据
     */
    public function gets(array $ids)
    {
        if (!$ids) {
            return [];
        }

        return $this->getSlave()->findAll($this->_tableName, $this->_columns, array($this->_primary=>$ids));
    }

    public function add(array $row)
    {
        $db = $this->getMaster();
        $db = $db->insert($this->_tableName, $row);
        if ($ok) {
            if (!isset($row[$this->_primary])) {
                return $db->lastInsertId();
            }
        }
        return $ok;
    }

    public function adds(array $rows)
    {
        $db = $this->getMaster();
        $ok = $db->inserts($this->_tableName, $rows);
        if ($ok) {
            $row = reset($rows);
            if (!isset($row[$this->_primary])) {
                return $db->lastInsertId();
            }
        }
        return $ok;
    }

    public function edit(string $id, $row)
    {
        return $this->getMaster()->update($this->_tableName, array($this->_primary=>$id), 1);
    }

    public function del(string $id)
    {
        return $this->getMaster()->delete($this->_tableName, array($this->_primary=>$id), 1);
    }

    protected function getMaster()
    {
        return $this->getDb($this->_dbName . "_master");
    }

    protected function getSlave()
    {
        return $this->getDb($this->_dbName . "_slave");
    }

    protected function getDb($name='default')
    {
        if (!isset($this->_context->dbConns[$name])) {
            $db = Resource::getDb($name);
            $this->_context->dbConns[$name] = $db;
        }

        return $this->_context->dbConns[$name];
    }

    //给框架内部提供些信息
    public function _getInfo()
    {
        return [$this->_dbName, $this->_tableName, $this->_primary, $this->_columns];
    }
}
