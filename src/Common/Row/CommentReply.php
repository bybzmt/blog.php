<?php
namespace Bybzmt\Blog\Service;

class CommentReply extends Base
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

        if ($row['user_id']) {
            $this->user = $this->getLazyRowCache('User', $row['user_id']);
        } else {
            $this->user = $this->getService('User')->guestUser($row['name']);
        }

        if ($row['reply_id']) {
            $this->reply = $this->getLazyRowCache('CommentReply', $row['reply_id']);
        }

        $this->content = $row['content'];
        $this->addtime = $row['addtime'];
        $this->status = (int)$row['status'];
    }

    public function del()
    {
        //删除自身数据
        $this->getTable('Blog.CommentReply')->update($this->id, ['status'=>2]);
        $this->status = 2;

        $this->getRowCache('CommentReply')->update(['status'=>2]);

        //将自身从评论回id缓存中去掉
        $this->getRowCache('Comment', $this->comment->id)->_removeCacheReplysId($this->id);
    }
}
