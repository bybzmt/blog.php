<?php
namespace Bybzmt\Blog\Common\Table;

use Bybzmt\Blog\Common;

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
        $sql = "select id from comments where article_id = ? AND status = 1 order by id desc limit $offset, $length";

        return $this->getSlave()->fetchColumnAll($sql, array($article_id));
    }

    //得到文章评论数量
    public function getArticleCommentNum(int $article_id)
    {
        $sql = "select count(*) from comments where article_id = ? AND status = 1";

        return $this->getSlave()->fetchColumn($sql, array($article_id));
    }


}
