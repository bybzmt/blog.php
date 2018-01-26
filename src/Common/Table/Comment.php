<?php
namespace Bybzmt\Blog\Common\Table;

use Flexihash\Flexihash;
use Bybzmt\Blog\Common;
use PDO;

class Comment extends Common\TableSplit
{
    use Common\TableRowCache;

    protected $_dbName = 'blog';
    protected $_tablePrefix = "article_comments_";
    protected $_tableNum = 3;
    protected $_primary = 'id';
    protected $_columns = [
        'id',
        'article_id',
        'comment_id',
        'user_id',
        'content',
        'addtime',
        'status',
        '_replys_id',
    ];

    //得到文章评论列表
    public function getListIds(int $article_id, int $offset, int $length)
    {
        $this->_setTable($article_id);

        $sql = "SELECT id FROM {$this->_tableName} WHERE article_id = ? AND comment_id = 0 AND status = 1 ORDER BY id DESC LIMIT $offset, $length";

        return $this->query($sql, [$article_id])->fetchAll(PDO::FETCH_COLUMN, 0);

        $out = array();
        foreach ($ids as $id) {
            $out[] = "$article_id:$id";
        }
        return $out;
    }

    //得到文章评论数量
    public function getListNum(int $article_id)
    {
        $this->_setTable($article_id);

        $sql = "SELECT count(*) FROM {$this->_tableName} WHERE article_id = ? AND status = 1";

        return $this->query($sql, array($article_id))->fetch(PDO::FETCH_COLUMN, 0);
    }

    public function getReplyIds(int $article_id, int $comment_id, int $offset, int $lenght)
    {
        $this->_setTable($article_id);

        $sql = "SELECT id FROM {$this->_tableName} WHERE comment_id = ? AND status = 1 ORDER BY id ASC LIMIT $offset, $lenght";

        return $this->query($sql, [$comment_id])->fetchAll(PDO::FETCH_COLUMN, 0);

        $out = array();
        foreach ($ids as $id) {
            $out[] = "$split_id:$id";
        }
        return $out;
    }

    //得到指定id所在分页
    public function getIdPage(int $article_id, int $comment_id, int $length)
    {
        $this->_setTable($article_id);

        $sql = "SELECT COUNT(*) FROM {$this->_tableName} WHERE article_id = ? and comment_id = 0 and status=1 and id >= ? ORDER BY id DESC";

        $count = $this->query($sql, [$article_id, $comment_id])->fetchColumn();

        return ceil($count / $length);
    }

    //得到指定id所在分页
    public function getReplyIdPage(int $article_id, int $comment_id, int $reply_id, int $length)
    {
        $this->_setTable($article_id);

        $sql = "SELECT COUNT(*) FROM {$this->_tableName} WHERE article_id = ? and comment_id = ? and status=1 and id <= ? ORDER BY id ASC";

        $count = $this->query($sql, [$article_id, $comment_id, $reply_id])->fetchColumn();

        return (int)ceil($count / $length);
    }


}
