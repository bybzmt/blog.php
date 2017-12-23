<?php
namespace Bybzmt\Blog\Common;

use PDO;
use Bybzmt\DB\Monitor;

/**
 * 数据库表
 */
abstract class Table
{
    protected $_context;

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
    public function get(string $id)
    {
        return $this->getSlave()->find($this->_tableName, $this->_columns, array($this->_primary=>$id));
    }

    /**
     * 按主键查找一批数据
     */
    public function gets(array $ids, $missEmpty=true)
    {
        if (!$ids) {
            return [];
        }

        $rows = $this->getSlave()->findAll($this->_tableName, $this->_columns, array($this->_primary=>$ids));

        $rows = array_column($rows, null, $this->_primary);

        //不能跳过空时
        if (!$missEmpty) {
            foreach (array_diff($ids, array_keys($rows)) as $id) {
                $rows[$id] = false;
            }
        }

        return $rows;
    }

    public function create(array $row)
    {
        $db = $this->getMaster();
        $ok = $db->insert($this->_tableName, $row);
        if ($ok) {
            if (!isset($row[$this->_primary])) {
                return $db->lastInsertId();
            }
        }
        return $ok;
    }

    public function update(string $id, array $row)
    {
        return $this->getMaster()->update($this->_tableName, $row, array($this->_primary=>$id), 1);
    }

    public function delete(string $id)
    {
        return $this->getMaster()->delete($this->_tableName, array($this->_primary=>$id), 1);
    }

    protected function getMaster()
    {
        return $this->_context->getDb($this->_dbName . "_master");
    }

    protected function getSlave()
    {
        return $this->_context->getDb($this->_dbName . "_slave");
    }

}
