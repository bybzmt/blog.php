<?php
namespace Bybzmt\Blog\Common\Table;

use Bybzmt\Blog\Common;
use PDO;

class Comment extends Common\TableRowCache
{
    protected $_dbName = 'blog';
    protected $_tableName = 'comments';
    protected $_primary = 'id';
    protected $_columns = [
        'id',
        'article_id',
        'user_id',
        'content',
        'addtime',
        'status',
        'cache_replys_id',
    ];

    //得到文章评论列表
    public function getList(int $article_id, int $offset, int $length)
    {
        $sql = "SELECT id FROM {$this->_tableName} WHERE article_id = ? AND status = 1 ORDER BY id DESC LIMIT $offset, $length";

        return $this->query($sql, [$article_id])->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    //得到文章评论数量
    public function getArticleCommentNum(int $article_id)
    {
        $sql = "SELECT count(*) FROM {$this->_tableName} WHERE article_id = ? AND status = 1";

        return $this->query($sql, array($article_id))->fetch(PDO::FETCH_COLUMN, 0);
    }


}
