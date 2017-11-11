<?php
namespace Bybzmt\Blog\Common\Row;

use Bybzmt\Blog\Common;

class CommentReply extends Common\Row
{
    public $id;
    public $comment;
    public $reply;
    public $user;
    public $content;
    public $addtime;
    public $status;

    protected function init($row)
    {
        $this->id = $row['id'];
        $this->comment = $this->getLazyRowCache('Comment', $row['comment_id']);
        $this->user = $this->getLazyRowCache('User', $row['user_id']);
        if ($row['reply_id']) {
            $this->reply = $this->getLazyRowCache('CommentReply', $row['reply_id']);
        }
        $this->content = $row['content'];
        $this->addtime = strtotime($row['addtime']);
        $this->status = (int)$row['status'];
    }

    public function del()
    {
        //删除自身数据
        $this->getTable('CommentReply')->update($this->id, ['status'=>2]);
        $this->status = 2;

        $this->getCache('RowCache', 'CommentReply')->update(['status'=>2]);

        //将自身从评论回id缓存中去掉
        $this->getRowCache('Comment', $this->comment->id)->_removeCacheReplysId($this->id);
    }
}
