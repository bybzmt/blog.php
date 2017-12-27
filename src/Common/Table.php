<?php
namespace Bybzmt\Blog\Common;

use PDO;
use PDOStatement;
use Bybzmt\Blog\Common\Helper\SQLBuilder;

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
        $sql = "SELECT `".implode("`,`", $this->_columns)."`
            FROM `{$this->_tableName}` WHERE `{$this->_primary}` = ?";

        return $this->query($sql, [$id])->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 按主键查找一批数据
     */
    public function gets(array $ids, $missEmpty=true)
    {
        if (!$ids) {
            return [];
        }

        list($sql, $params) = SQLBuilder::select($this->_columns, $this->_tableName, [$this->_primary=>$ids]);

        $rows = $this->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);

        $rows = array_column($rows, null, $this->_primary);

        //不能跳过空时
        if (!$missEmpty) {
            foreach (array_diff($ids, array_keys($rows)) as $id) {
                $rows[$id] = false;
            }
        }

        return $rows;
    }

    public function insert(array $row)
    {
        if (!$row) {
            return false;
        }

        list($sql, $vals) = SQLBuilder::insert($this->_tableName, $row);

        $affected = $this->exec($sql, $vals);
        if ($affected) {
            if (!isset($row[$this->_primary])) {
                return $this->getDB(true)->lastInsertId();
            }
        }
        return $affected;
    }

	public function inserts(array $rows)
	{
        if (!$rows) {
            return false;
        }

        list($sql, $vals) = SQLBuilder::inserts($this->_tableName, $rows);

        $affected = $this->exec($sql, $vals);
        if ($affected) {
            if (!isset($row[$this->_primary])) {
                return $this->getDB(true)->lastInsertId();
            }
        }
        return $affected;
	}

    public function update(string $id, array $row)
    {
        if (!$row) {
            return false;
        }

        list($sql, $vals) = SQLBuilder::update($this->_tableName, $row, [$this->_primary=>$id]);

        return $this->exec($sql, $vals);
    }

    public function delete(string $id)
    {
		$sql = "DELETE FROM `{$this->_tableName}` WHERE `{$this->_primary}` = ? LIMIT 1";

        return $this->exec($sql, [$id]);
    }

    protected function getDB(bool $isMaster=false)
    {
        return $this->_context->getDb($this->_dbName . ($isMaster?'_master':'_slave'));
    }

    protected function query(string $sql, array $params=[], bool $isMaster=false):PDOStatement
    {
        if ($params) {
            $stmt = $this->getDB($isMaster)->prepare($sql);
            if (!$stmt) {
                return false;
            }

            $ok = $stmt->execute($params);
            if (!$ok) {
                return false;
            }

            return $stmt;
        } else {
            return $this->getDB()->query($sql);
        }
    }

    protected function exec(string $sql, array $params=[])
    {
        if ($params) {
            $stmt = $this->getDB()->prepare($sql);
            if (!$stmt) {
                return false;
            }

            $ok = $stmt->execute($params);
            if (!$ok) {
                return false;
            }

            return $stmt->rowCount();
        } else {
            return $this->getDB()->exec($sql);
        }
    }

}
