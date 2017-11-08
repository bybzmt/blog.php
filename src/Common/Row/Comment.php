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
        $this->user = $this->getLazyRowCache('User', $row['user_id']);
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
            $ids = $this->getTable('CommentsReply')->getReplyIds($this->id, $offset, $length);
        }

        $rows = [];
        foreach ($ids as $id) {
            $rows[] = $this->getLazyRowCache('CommentsReply', $id);
        }
        return $rows;
    }

    public function addReply(User $user, ?CommentReply $reply, string $content)
    {
        $data = array(
            'comment_id' => $this->id,
            'reply_id' => $reply ? $reply->id : 0,
            'user_id' => $user->id,
            'content' => $content,
            'addtime' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
            'status' => 1,
        );

        //保存数据
        $id = $this->getTable('CommentsReply')->insert($data);
        if (!$id) {
            return false;
        }

        //更新缓存
        $data['id'] = $id;
        $this->getCache('RowCache', 'CommentReply')->set($id, $data);

        //给被回复的评论修改缓存记录
        if (count($this->_cache_replys_id) < self::max_cache_replys_num) {
            $this->_cache_replys_id[] = $id;
            $this->_setCacheReplysId($this->_cache_replys_id);
        }
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
                $ids = $this->getTable('CommentReply')->getReplyIds($this->id, 0, self::max_cache_replys_num);
            }

            $this->_setCacheReplysId($ids);
        }
    }

    public function del()
    {
        //标记删除
        $this->getTable('Article')->update($this->id, ['status'=>2]);
        $this->status = 2;

        $this->getCache('RowCache', 'Comment')->update(['status'=>2]);

        //从文章评论列表中删除缓存
        $this->getListCache('ArticleComments', $this->article->id)->delItem($this->id);
    }
}
