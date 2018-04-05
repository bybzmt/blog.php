<?php
namespace Bybzmt\Blog\Common\Row;

use Bybzmt\Framework\Row;

class Reply extends Row
{
    public function getArticle()
    {
        $article = $this->getRow("Article", $this->article_id);
        if (!$article) {
            throw new Exception("Row Reply:{$this->id} 关联 Article:{$this->article_id} 不存在");
        }
        return $article;
    }

    public function getComment()
    {
        $comment = $this->getRow("Comment", $this->article_id.":".$this->comment_id);
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

        $reply = $this->getRow("Reply", $this->comment_id.":".$this->reply_id);
        if (!$reply) {
            throw new Exception("Row Reply:{$this->id} 关联 Reply:{$this->comment_id}:{$this->reply_id} 不存在");
        }
        return $reply;
    }

    public function getUser()
    {
        $user = $this->getRow("User", $this->user_id);
        if (!$user) {
            throw new Exception("Row Reply:{$this->id} 关联 User:{$this->user_id} 不存在");
        }
        return $user;
    }

    public function del()
    {
        //标记删除
        $ok = $this->getTable('Reply')->update($this->comment_id.":".$this->id, ['status'=>0]);
        if ($ok) {
            $this->status = 0;

            //判断是回复文章还是回复评论的
            $this->getComment()->_removeCacheReplysId($this->id);
        }

        return $ok;
    }

    //恢复评论
    public function restore()
    {
        $ok = $this->getTable("Reply")->update($this->comment_id.":".$this->id, array('status'=>1));
        if ($ok) {
            $this->status = 1;

            //重置文章的评论缓存
            $this->getComment()->_restCacheReplysId();
        }

        return $ok;
    }

    public function getCurrentPage($length)
    {
        return $this->getTable("Reply")
            ->getIdPage($this->comment_id, $this->id, $length);
    }

}
