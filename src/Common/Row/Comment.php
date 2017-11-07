<?php
namespace Bybzmt\Blog\Common\Row;

use Bybzmt\Blog\Common;

class Comment extends Common\Row
{
    const max_cache_replys_num=60;

    public $id;
    public $article;
    public $user;
    public $content;
    public $addtime;
    public $status;

    private $_cache_replys_id;

    protected function init(array $row)
    {
        $this->id = $row['id'];
        $this->article = $this->getLazyRowCache('Article', $row['article_id']);

        if ($row['user_id']) {
            $this->user = $this->getLazyRowCache('User', $row['user_id']);
        } else {
            $this->user = $this->getService('User')->guestUser($row['name']);
        }

        $this->content = $row['content'];
        $this->addtime = strtotime($row['addtime']);
        $this->status = (int)$row['status'];

        $this->_cache_replys_id = unpack('N*', $this->cache_replys_id);
    }

    public function getReply(int $offset, int $length)
    {
        if ($offset+$length <= count($this->_cache_replys_id)) {
            $ids = array_slice($this->_cache_replys_id, $offset, $length);
        } else {
            $ids = $this->getTable('Blog.CommentsReply')->getReplyIds($this->id, $offset, $length);
        }

        $rows = [];
        foreach ($ids as $id) {
            $rows[] = $this->getLazyRowCache('CommentsReply', $id);
        }
        return $rows;
    }

    public function addReply(User $user, string $content)
    {
        //保存数据
        $id = $this->getTable('Blog.CommentsReply')->addReply($this->id, $user->id, $user->nickname, $content);

        if (count($this->_cache_replys_id) < self::max_cache_replys_num) {
            $this->_cache_replys_id[] = $id;
            $this->_setCacheReplysId($this->_cache_replys_id);
        }

        //更新缓存
        $this->getRowCache('CommentsReply', $id)->del();
    }

    private function _setCacheReplysId(array $ids)
    {
        $str = "";
        foreach ($ids as $id) {
            $str .= pack("N", $id);
        }

        //更新评论记录
        $this->getTable('CommentsReply')->update($this->id, ['cache_replys_id'=>$str]);

        //更新评论缓存
        $this->getRowCache('Comment', $this->id)->update(['cache_replys_id'=>$str]);
    }

    public function _removeCacheReplysId(int $id)
    {
        if (array_search($id, $this->_cache_replys_id) !== false) {
            if (count($this->_cache_replys_id) < self::max_cache_replys_num) {
                $ids = array_diff($this->_cache_replys_id, [$id]);
            } else {
                $ids = $this->getTable('Blog.CommentReply')->getReplyIds($this->id, 0, self::max_cache_replys_num);
            }

            $this->_setCacheReplysId($ids);
        }
    }

    public function del()
    {
        //删除自身数据
        $this->getTable('Blog.Article')->update($this->id, ['status'=>2]);
        $this->status = 2;

        $this->getRowCache('Comment')->update(['status'=>2]);

        //添加到列表缓存
        $this->getListCache('ArticleComments', $this->article->id)->remove($this->id);
    }
}
