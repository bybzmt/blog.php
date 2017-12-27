<?php
namespace Bybzmt\Blog\Common\Row;

use Bybzmt\Blog\Common;

class Article extends Common\Row
{
    const max_tag_num=60;

    public $id;
    public $author;
    public $title;
    public $intro;
    public $content;
    public $addtime;
    public $edittime;
    public $status;
    public $locked;
    public $deleted;
    public $top;

    private $_cache_comments_num;
    private $_cache_tags;

    protected function init(array $row)
    {
        $this->id = $row['id'];
        $this->title = $row['title'];
        $this->intro = $row['intro'];
        $this->content = $row['content'];
        $this->addtime = strtotime($row['addtime']);
        $this->edittime = strtotime($row['edittime']);
        $this->top = (bool)$row['top'];
        $this->status = (int)$row['status'];
        $this->locked = (bool)$row['locked'];
        $this->deleted = (bool)$row['deleted'];
        $this->author = $this->_context->getLazyRow('User', $row['user_id']);
        $this->_cache_comments_num = (int)$row['cache_comments_num'];
        $this->_cache_tags = $row['cache_tags'];
    }

    public function getCommentsNum()
    {
        return $this->_cache_comments_num;
    }

    public function getComments(int $offset, int $length)
    {
        return $this->_context->getCache('ArticleComments', $this->id)->gets($offset, $length);
    }

    public function addComment(User $user, string $content)
    {
        $data = array(
            'article_id' => $this->id,
            'user_id' => $user->id,
            'content' => $content,
            'addtime' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
            'status' => 1,
            'cache_replys_id' => '',
        );

        //保存回复
        $id = $this->_context->getTable('Comment')->insert($data);

        if ($id) {
            //修改文章回复数缓存
            $this->_context->getTable('Article')->incrCommentsNum($this->id, 1);

            $this->_cache_comments_num++;

            //添加到列表缓存
            $this->_context->getCache('ArticleComments', $this->id)->addItem($id);
        }

        return $id;
    }

    //从文章的评论缓存中删除评论
    public function delCommentCache(int $comment_id)
    {
        //修改数据
        $ok = $this->_context->getTable('Article')->decrCommentsNum($this->id, 1);
        if ($ok) {
            //内存中的变量
            $this->_cache_comments_num--;
            //文章评论列表缓存重置
            $this->_context->getCache('ArticleComments', $this->id)->delItem($comment_id);
        }
        return $ok;
    }

    //重置文章的评论缓存
    public function restCommentCacheNum()
    {
        //重新统计表中的评论数量
        $num = $this->_context->getTable("Comment")->getArticleCommentNum($this->id);
        //修改数据
        $ok = $this->_context->getTable("Article")->update($this->id, ['cache_comments_num'=>$num]);
        if ($ok) {
            //文章评论列表缓存重置
            $this->_context->getCache('ArticleComments', $this->id)->del();
            //当前变量
            $this->_cache_comments_num = $num;
        }
        return $ok;
    }

    public function getTags()
    {
        $tag_ids = unpack('N*', $this->_cache_tags);

        $tags = [];
        foreach ($tag_ids as $tag_id) {
            $tags[] = $this->_context->getLazyRow('Tag', $tag_id);
        }
        return $tags;
    }

    public function setTags(array $tags)
    {
        if (count($tags) > self::max_tag_num) {
            return false;
        }

        $tag_sids = '';
        $tag_ids = [];
        foreach ($tags as $tag) {
            $tag_ids[] = $tag->id;
            $tag_sids .= pack("N", $tag->id);
        }

        //修改关连关系
        $ok = $this->_context->getTable('ArticleTag')->setTags($this->id, $tag_ids);
        if ($ok) {
            //修改关连缓存
            $this->_context->getTable('Article')->update($this->id, ['cache_tags' => $tag_sids]);

            //修改行缓存
            $this->_context->getCache('RowCache', 'Article')->update($this->id, ['cache_tags' => $tag_sids]);

            //修改当前对像
            $this->_cache_tags = $tag_sids;

            return true;
        }
        return false;
    }

    //申请审核
    public function requestAudit()
    {
        //仅草稿和下线的可以申请审核
        if (!in_array($this->status, [1,4])) {
            return false;
        }

        //标记删除自身数据
        $ok = $this->_context->getTable('Article')->update($this->id, ['status'=>2]);
        if ($ok) {
            //审核标记
            $this->status = 2;
        }

        return $ok;
    }

    //正式发布
    public function publish()
    {
        //修改自身数据
        $ok = $this->_context->getTable('Article')->update($this->id, ['status'=>3]);
        if ($ok) {
            //状态改为正式
            $this->status = 3;

            //添加到首页缓存
            $this->_context->getCache('IndexArticles')->addItem($this->id);

            //添加到标签列表缓存
            foreach ($this->getTags() as $tag) {
                $this->_context->getCache('TagArticles', $tag->id)->addItem($this->id);
            }
        }

        return $ok;
    }

    //文章下线
    public function hidden()
    {
        //标记删除自身数据
        $ok = $this->_context->getTable('Article')->update($this->id, ['status'=>4]);
        if ($ok) {
            //记标为下线
            $this->status = 4;

            //删除标签列表缓存
            foreach ($this->getTags() as $tag) {
                $this->_context->getCache('TagArticles', $tag->id)->delItem($this->id);
            }

            //删除首页缓存
            $this->_context->getCache('IndexArticles')->delItem($this->id);
        }

        return $ok;
    }

    //锁定文章
    public function locked()
    {
        //记标为锁定状态
        $ok = $this->_context->getTable('Article')->update($this->id, ['locked'=>1]);
        if ($ok) {
            $this->locked = 1;
        }

        return $ok;
    }

    //解除锁定
    public function unlock()
    {
        //记标为解除锁定
        $ok = $this->_context->getTable('Article')->update($this->id, ['locked'=>0]);
        if ($ok) {
            $this->locked = 0;
        }

        return $ok;
    }

    //删除文章
    public function del()
    {
        //标记删除自身数据
        $ok = $this->_context->getTable('Article')->update($this->id, ['deleted'=>1]);
        if ($ok) {
            //记标为删除
            $this->deleted = 1;

            //删除标签列表缓存
            foreach ($this->getTags() as $tag) {
                $this->_context->getCache('TagArticles', $tag->id)->delItem($this->id);
            }

            //删除首页缓存
            $this->_context->getCache('IndexArticles')->delItem($this->id);
        }

        return $ok;
    }


}
