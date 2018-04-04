<?php
namespace Bybzmt\Blog\Common\Row;

use Bybzmt\Framework\Row;

class Comment extends Row
{
    const max_cache_replys_num=60;

    public function getArticle()
    {
        $article = $this->getRow("Article", $this->article_id);
        if (!$article) {
            throw new Exception("Row Comment:{$this->id} 关联 Article:{$this->article_id} 不存在");
        }
        return $article;
    }

    public function getUser()
    {
        $user = $this->getRow("User", $this->user_id);
        if (!$user) {
            throw new Exception("Row Comment:{$this->id} 关联 User:{$this->user_id} 不存在");
        }
        return $user;
    }

    public function getReplys(int $offset, int $length)
    {
        if ($offset+$length <= intval(strlen($this->_replys_id)/4)) {
            $ids = array_slice($this->_getCacheReplyIds(), $offset, $length);
        } else {
            $ids = $this->getTable('Reply')->getListIds($this->id, $offset, $length);
        }

        $rows = [];
        foreach ($ids as $id) {
            $rows[] = $this->getLazyRow('Reply', $this->id.":".$id);
        }
        return $rows;
    }

    public function addReply(User $user, string $content, Reply $reply=null)
    {
        $data = array(
            'id' => "{$this->id}:",
            'article_id' => $this->article_id,
            'comment_id' => $this->id,
            'reply_id' => $reply ? $reply->id : 0,
            'user_id' => $user->id,
            'content' => $content,
            'status' => 1,
        );

        //保存数据
        $id = $this->getTable('Reply')->insert($data);
        if (!$id) {
            return false;
        }

        //给用户增加发评论的关联记录
        $this->getTable("Record")->insert(array(
            'id' => "{$user->id}:",
            'user_id' => $user->id,
            'type' => Record::TYPE_REPLY,
            'to_id' => $this->id.":".$id,
        ));

        //给被回复的评论修改缓存记录
        $this->_addCacheReplyId($id);

        return true;
    }

    public function del()
    {
        //标记删除
        $ok = $this->getTable('Comment')->update($this->article_id.":".$this->id, ['status'=>0]);
        if ($ok) {
            $this->status = 0;

            //删除文章中的评论缓存
            $this->getArticle()->delCommentCache($this->id);
        }

        return $ok;
    }

    //恢复评论
    public function restore()
    {
        $ok = $this->getTable("Comment")->update($this->article_id.":".$this->id, array('status'=>1));
        if ($ok) {
            $this->status = 1;

            //重置文章的评论缓存
            $this->getArticle()->restCommentCacheNum();
        }

        return $ok;
    }

    public function getCurrentPage($length)
    {
        return $this->getTable("Comment")
            ->getIdPage($this->article_id, $this->id, $length);
    }


    public function _restCacheReplysId()
    {
        $ids = $this->getTable('Reply')->getListIds($this->id, 0, self::max_cache_replys_num);
        $this->_setCacheReplyIds($ids);
    }

    public function _removeCacheReplysId(int $id)
    {
        $replyIds = $this->_getCacheReplyIds();

        if (array_search($id, $replyIds) !== false) {
            if (count($replyIds) < self::max_cache_replys_num) {
                $ids = array_diff($replyIds, [$id]);
            } else {
                $ids = $this->getTable('Reply')->getListIds($this->id, 0, self::max_cache_replys_num);
            }

            $this->_setCacheReplyIds($ids);
        }
    }

    protected function _getCacheReplyIds()
    {
        return unpack('N*', $this->_replys_id);
    }

    protected function _addCacheReplyId(string $id)
    {
        //给被回复的评论修改缓存记录
        if (intval(strlen($this->_replys_id)/4) < self::max_cache_replys_num) {
            $this->_replys_id .= pack("N", $id);

            $this->getTable('Comment')->update($this->article_id.":".$this->id, ['_replys_id'=>$this->_replys_id]);
        }
    }

    protected function _setCacheReplyIds(array $ids)
    {
        $str = "";
        foreach ($ids as $id) {
            $str .= pack("N", $id);
        }

        //更新评论记录
        $this->getTable('Comment')->update($this->article_id.":".$this->id, ['_replys_id'=>$str]);
    }
}
