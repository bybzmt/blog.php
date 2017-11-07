<?php
namespace Bybzmt\Blog\Common;

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
    public function find($id)
    {
        $columns = implode("`,`", $this->_columns);

        $sql = "SELECT `$columns` FROM `{$this->_tableName}` WHERE `{$this->_primary}` = ? LIMIT 1";

        $stmt = $this->getSlave()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 按主键查找一批数据
     */
    public function finds(array $ids)
    {
        if (!$ids) {
            return [];
        }

        $columns = implode("`,`", $this->_columns);
        $holds = implode(',', array_fill(0, count($ids), '?'));

        $sql = "SELECT `$columns` FROM `{$this->_tableName}` WHERE `{$this->_primary}` IN ($holds)";

        $stmt = $this->getSlave()->prepare($sql);
        $stmt->execute($ids);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert(array $row)
    {
		$keys = implode('`, `', array_keys($row));
        $holds = implode(',', array_fill(0, count($row), '?'));
		$vals = array_values($row);

		$sql = "INSERT INTO {$this->_tableName} (`{$keys}`) VALUES({$holds})";

        $db = $this->getMaster();
        $stmt = $db->prepare($sql);
        $ok = $stmt->execute($vals);
        if ($ok) {
            if (!isset($row[$this->_primary])) {
                return $db->lastInsertId();
            }
        }
        return $ok;
    }

    public function inserts($rows)
    {
		$holds = array();

		$feilds = array_keys(reset($values));

        $hold = implode(',', array_fill(0, count($feilds), '?'));
		$holds = implode(",\n", array_fill(0, count($rows), $hold));
		$feilds = implode("`,`", $feilds);

        $vals = [];
        foreach ($rows as $row) {
            $vals = array_merge($vals, array_values($row));
        }

		$sql = "INSERT INTO `{$this->_tableName}` (`{$feilds}`)\n VALUES {$holds}";

        $db = $this->getMaster();
        $stmt = $db->prepare($sql);
        $ok = $stmt->execute($vals);
        if ($ok) {
            if (!isset($row[$this->_primary])) {
                return $db->lastInsertId();
            }
        }
        return $ok;
    }

    public function update(string $id, $row)
    {
        if (!$row) {
            return;
        };

		$set = array();
        $vals = array();

		foreach ($feilds as $key => $val) {
			$set[] = "`{$key}` = ?";
            $vals[] = $val;
		}

		$set = implode(', ', $set);
        $vals[] = $id;

		$sql = "UPDATE `{$this->_tableName}` SET {$set} WHERE `{$this->_primary}` = ? LIMIT 1";

        $stmt = $this->getMaster()->prepare($sql);
        return $stmt->execute($vals);
    }

    public function del(string $id)
    {
		$sql = "DELETE FROM `{$this->_tableName}` WHERE `{$this->_primary}` = ? LIMIT 1";

        $stmt = $this->getMaster()->prepare($sql);
        return $stmt->execute([$id]);
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
            $this->_context->dbConns[$name] = Resource::getDb($name);
        }

        return $this->_context->dbConns[$name];
    }

    //给框架内部提供些信息
    public function _getInfo()
    {
        return [$this->_dbName, $this->_tableName, $this->_primary, $this->_columns];
    }
}
