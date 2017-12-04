<?php
namespace Bybzmt\Blog\Common\Table;

use Bybzmt\Blog\Common;

class Comment extends Common\Table
{
    protected $_dbName = 'blog';
    protected $_tableName = 'comment_replys';
    protected $_primary = 'id';
    protected $_columns = [
        'id',
        'comment_id',
        'reply_id',
        'user_id',
        'content',
        'addtime',
        'status',
    ];

    public function getReplyIds(int $comment_id, int $offset, int $length)
    {
        $sql = "select id from comment_replys where comment_id = ? AND status = 1 order by id desc limit $offset, $length";

        return $this->getSlave()->fetchColumnAll($sql);
    }


}
