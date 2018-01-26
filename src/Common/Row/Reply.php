<?php
namespace Bybzmt\Blog\Common\Row;

use Bybzmt\Blog\Common;

class Reply extends Common\Row
{
    public function getArticle()
    {
        $article = $this->_context->getRow("Article", $this->article_id);
        if (!$article) {
            throw new Exception("Row Reply:{$this->id} 关联 Article:{$this->article_id} 不存在");
        }
        return $article;
    }

    public function getComment()
    {
        $comment = $this->_context->getRow("Comment", $this->article_id.":".$this->comment_id);
        if (!$comment) {
            throw new Exception("Row Reply:{$this->id} 关联 Comment:{$this->article_id}:{$this->comment_id} 不存在");
        }
        return $comment;
    }

    public function getReply()
    {
        if (!$this->reply_id) {
            return false;
        }

        $reply = $this->_context->getRow("Reply", $this->comment_id.":".$this->reply_id);
        if (!$reply) {
            throw new Exception("Row Reply:{$this->id} 关联 Reply:{$this->comment_id}:{$this->reply_id} 不存在");
        }
        return $reply;
    }

    public function getUser()
    {
        $user = $this->_context->getRow("User", $this->user_id);
        if (!$user) {
            throw new Exception("Row Reply:{$this->id} 关联 User:{$this->user_id} 不存在");
        }
        return $user;
    }

    public function del()
    {
        //标记删除
        $ok = $this->_context->getTable('Reply')->update($this->comment_id.":".$this->id, ['status'=>0]);
        if ($ok) {
            $this->status = 0;

            //判断是回复文章还是回复评论的
            $this->comment->_removeCacheReplysId($this->id);
        }

        return $ok;
    }

    //恢复评论
    public function restore()
    {
        $ok = $this->_context->getTable("Reply")->update($this->comment_id.":".$this->id, array('status'=>1));
        if ($ok) {
            $this->status = 1;

            //重置文章的评论缓存
            $this->comment->_restCacheReplysId();
        }

        return $ok;
    }

    public function getCurrentPage($length)
    {
        return $this->_context->getTable("Reply")
            ->getIdPage($this->comment_id, $this->id, $length);
    }

}
