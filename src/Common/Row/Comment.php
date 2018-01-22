<?php
namespace Bybzmt\Blog\Common\Row;

use Bybzmt\Blog\Common;

class Comment extends Common\Row
{
    const max_cache_replys_num=60;

    public function getReply(int $offset, int $length)
    {
        if ($offset+$length <= intval(strlen($this->_replys_id)/4)) {
            $ids = array_slice($this->_getCacheReplyIds(), $offset, $length);
        } else {
            $ids = $this->_context->getTable('Comment')->getReplyIds($this->article_id, $this->id, $offset, $length);
        }

        $rows = [];
        foreach ($ids as $id) {
            $rows[] = $this->_context->getLazyRow('Comment', $this->article_id.":".$id);
        }
        return $rows;
    }

    public function addReply(User $user, string $content)
    {
        $comment = $this->_getComment();
        if (!$comment) {
            return false;
        }

        $data = array(
            'id' => "{$this->article_id}:",
            'article_id' => $this->article_id,
            'comment_id' => $comment->id,
            'reply_id' => $this->id,
            'user_id' => $user->id,
            'content' => $content,
            'status' => 1,
            '_replys_id' => '',
        );

        //保存数据
        $id = $this->_context->getTable('Comment')->insert($data);
        if (!$id) {
            return false;
        }

        //给用户增加发评论的关联记录
        $this->_context->getTable("Record")->insert(array(
            'id' => "{$user->id}:",
            'user_id' => $user->id,
            'type' => 1,
            'to_id' => $this->article_id.":".$id,
        ));

        //给被回复的评论修改缓存记录
        $comment->_addCacheReplyId($id);

        return true;
    }


    public function del()
    {
        //标记删除
        $ok = $this->_context->getTable('Comment')->update($this->article_id.":".$this->id, ['status'=>0]);
        if ($ok) {
            $this->status = 0;

            //判断是回复文章还是回复评论的
            if ($this->comment_id == 0) {
                //删除文章中的评论缓存
                $this->article->delCommentCache($this->id);
            } else {
                $comment = $this->_getComment();
                if (!$comment) {
                    return false;
                }
                $comment->_removeCacheReplysId($this->id);
            }
        }

        return $ok;
    }

    //恢复评论
    public function restore()
    {
        $ok = $this->_context->getTable("Comment")->update($this->article_id.":".$this->id, array('status'=>1));
        if ($ok) {
            $this->status = 1;

            //重置文章的评论缓存
            $this->article->restCommentCacheNum();
        }

        return $ok;
    }

    public function getCurrentPage($length)
    {
        if ($this->comment_id == 0) {
            return $this->_context->getTable("Comment")
                ->getIdPage($this->article_id, $this->id, $length);
        } else {
            return $this->_context->getTable("Comment")
                ->getReplyIdPage($this->article_id, $this->comment_id, $this->id, $length);
        }
    }

    protected function _getCacheReplyIds()
    {
        return unpack('N*', $this->_replys_id);
    }

    public function _removeCacheReplysId(int $id)
    {
        $replyIds = $this->_getCacheReplyIds();

        if (array_search($id, $replyIds) !== false) {
            if (count($replyIds) < self::max_cache_replys_num) {
                $ids = array_diff($replyIds, [$id]);
            } else {
                $ids = $this->_context->getTable('Comment')->getReplyIds($this->article_id, $this->id, 0, self::max_cache_replys_num);
            }

            $this->_setCacheReplyIds($ids);
        }
    }

    protected function _addCacheReplyId(string $id)
    {
        //给被回复的评论修改缓存记录
        if (intval(strlen($this->_replys_id)/4) < self::max_cache_replys_num) {
            $this->_replys_id .= pack("N", $id);

            $this->_context->getTable('Comment')->update($this->article_id.":".$this->id, ['_replys_id'=>$this->_replys_id]);
        }
    }

    protected function _setCacheReplyIds(array $ids)
    {
        $str = "";
        foreach ($ids as $id) {
            $str .= pack("N", $id);
        }

        //更新评论记录
        $this->_context->getTable('Comment')->update($this->article_id.":".$this->id, ['_replys_id'=>$str]);
    }

    //找到最外层的评被回复评论
    protected function _getComment()
    {
        $comment = $this;
        while ($comment->reply_id != 0) {
            $comment = $this->_context->getRow("Comment", $this->article_id.":".$comment->reply_id);
            if (!$comment) {
                return false;
            }
        }
        return $comment;
    }
}
