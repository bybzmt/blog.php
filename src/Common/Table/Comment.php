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
        'user_id',
        'content',
        'addtime',
        'status',
        '_replys_id',
    ];

    //得到文章评论列表
    public function getList(int $article_id, int $offset, int $length)
    {
        $this->_setTable($article_id);

        $sql = "SELECT id FROM {$this->_tableName} WHERE article_id = ? AND status = 1 ORDER BY id DESC LIMIT $offset, $length";

        $ids = $this->query($sql, [$article_id])->fetchAll(PDO::FETCH_COLUMN, 0);

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


}
