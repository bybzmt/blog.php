<?php
namespace Bybzmt\Blog\Common\Table;

use Flexihash\Flexihash;
use Bybzmt\Blog\Common;
use PDO;

class Reply extends Common\TableSplit
{
    use Common\TableRowCache;

    protected $_dbName = 'blog';
    protected $_tablePrefix = "article_replys_";
    protected $_tableNum = 3;
    protected $_primary = 'id';
    protected $_columns = [
        'id',
        'article_id',
        'comment_id',
        'reply_id',
        'user_id',
        'content',
        'addtime',
        'status',
    ];

    public function getListIds(int $comment_id, int $offset, int $lenght)
    {
        $this->_setTable($comment_id);

        $sql = "SELECT id FROM {$this->_tableName} WHERE comment_id = ? AND status = 1 ORDER BY id ASC LIMIT $offset, $lenght";

        return $this->query($sql, [$comment_id])->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    //得到指定id所在分页
    public function getIdPage(int $comment_id, int $reply_id, int $length)
    {
        $this->_setTable($comment_id);

        $sql = "SELECT COUNT(*) FROM {$this->_tableName} WHERE comment_id = ? and status=1 and id <= ? ORDER BY id ASC";

        $count = $this->query($sql, [$comment_id, $reply_id])->fetchColumn();

        return (int)ceil($count / $length);
    }


}
