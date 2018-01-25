<?php
namespace Bybzmt\Blog\Common\Row;

use Bybzmt\Blog\Common;

class Reply extends Common\Row
{
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
