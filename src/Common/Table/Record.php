<?php
namespace Bybzmt\Blog\Common\Table;

use Bybzmt\Blog\Common;

class Record extends Common\TableSplit
{
    use Common\TableRowCache;

    protected $_dbName = 'blog';
    protected $_tablePrefix = "user_records_";
    protected $_tableNum = 3;
    protected $_primary = 'id';
    protected $_columns = [
        'id',
        'user_id',
        'type',
        'to_id',
    ];

    public function getList($user_id, int $offset, int $lenght)
    {
        $this->_setTable($user_id);

        $sql = "select type, to_id from {$this->_tableName}
            where user_id = ? order by id desc limit $offset, $lenght";

        return $this->query($sql, [$user_id])->fetchAll();
    }

    public function getListCount($user_id)
    {
        $this->_setTable($user_id);

        $sql = "select count(*) from {$this->_tableName}
            where user_id = ?";

        return $this->query($sql, [$user_id])->fetchColumn();
    }

}
