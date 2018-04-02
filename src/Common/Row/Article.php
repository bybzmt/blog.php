<?php
namespace Bybzmt\Blog\Common\Row;

use Bybzmt\Framework\Row;

class Article extends Row
{
    const max_tag_num=60;

    public function getUser()
    {
        $user = $this->getRow("User", $this->user_id);
        if (!$user) {
            throw new Exception("Row Article:{$this->id} 关联 User:{$this->user_id} 不存在");
        }
        return $user;
    }

    public function getCommentsNum()
    {
        return $this->_comments_num;
    }

    public function getComments(int $offset, int $length)
    {
        return $this->getCache('ArticleComments', $this->id)->gets($offset, $length);
    }

    public function edit($title, $intro, $content)
    {
        $data = array(
            'title' => $title,
            'intro' => $intro,
            'content' => $content,
        );

        $ok = $this->getTable("Article")->update($this->id, $data);
        if ($ok) {
            $this->title = $title;
            $this->intro = $intro;
            $this->content = $content;
        }
        return $ok;
    }

    public function addComment(User $user, string $content)
    {
        $data = array(
            'id' => "{$this->id}:",
            'article_id' => $this->id,
            'user_id' => $user->id,
            'comment_id' => 0,
            'content' => $content,
            'status' => 1,
            '_replys_id' => '',
        );

        //保存回复
        $id = $this->getTable('Comment')->insert($data);

        if ($id) {
            //给用户增加发评论的关联记录
            $this->getTable("Record")->insert(array(
                'id' => "{$user->id}:",
                'user_id' => $user->id,
                'type' => Record::TYPE_COMMENT,
                'to_id' => $this->id.":".$id,
            ));

            //定时重置数量，自动纠正错误
            if (mt_rand(1,10) == 1) {
                $this->restCommentCacheNum();
            } else {
                //修改文章回复数缓存
                $this->getTable('Article')->incrCommentsNum($this->id, 1);

                $this->_comments_num++;
            }

            //添加到列表缓存
            $this->getCache('ArticleComments', $this->id)->itemLPush($this->id.":".$id);
        }

        return $id;
    }

    //从文章的评论缓存中删除评论
    public function delCommentCache(int $comment_id)
    {
        //修改数据
        $ok = $this->getTable('Article')->decrCommentsNum($this->id, 1);
        if ($ok) {
            //内存中的变量
            $this->_comments_num--;
            //文章评论列表缓存重置
            $this->getCache('ArticleComments', $this->id)->delItem($comment_id);
        }
        return $ok;
    }

    //重置文章的评论缓存
    public function restCommentCacheNum()
    {
        //重新统计表中的评论数量
        $num = $this->getTable("Comment")->getListNum($this->id);
        //修改数据
        $ok = $this->getTable("Article")->update($this->id, ['_comments_num'=>$num]);
        if ($ok) {
            //文章评论列表缓存重置
            $this->getCache('ArticleComments', $this->id)->del();
            //当前变量
            $this->_comments_num = $num;
        }
        return $ok;
    }

    public function getTagsId()
    {
        return array_values(unpack('N*', $this->_tags));
    }

    public function getTags()
    {
        $tag_ids = unpack('N*', $this->_tags);

        return $this->getLazyRows('Tag', $tag_ids);
    }

    public function setTags(Tag ...$tags)
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
        $ok = $this->getTable('ArticleTag')->setTags($this->id, $tag_ids);
        if ($ok) {
            //修改关连缓存
            $this->getTable('Article')->update($this->id, ['_tags' => $tag_sids]);

            //修改当前对像
            $this->_tags = $tag_sids;

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
        $ok = $this->getTable('Article')->update($this->id, ['status'=>2]);
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
        $ok = $this->getTable('Article')->update($this->id, ['status'=>3]);
        if ($ok) {
            //状态改为正式
            $this->status = 3;

            //添加到首页缓存
            $this->getCache('IndexArticles')->itemLPush($this->id);

            //添加到标签列表缓存
            foreach ($this->getTags() as $tag) {
                $this->getCache('TagArticles', $tag->id)->itemLPush($this->id);
            }
        }

        return $ok;
    }

    //文章下线
    public function hidden()
    {
        //标记删除自身数据
        $ok = $this->getTable('Article')->update($this->id, ['status'=>4]);
        if ($ok) {
            //记标为下线
            $this->status = 4;

            //删除标签列表缓存
            foreach ($this->getTags() as $tag) {
                $this->getCache('TagArticles', $tag->id)->delItem($this->id);
            }

            //删除首页缓存
            $this->getCache('IndexArticles')->delItem($this->id);
        }

        return $ok;
    }

    //锁定文章
    public function locked()
    {
        //记标为锁定状态
        $ok = $this->getTable('Article')->update($this->id, ['locked'=>1]);
        if ($ok) {
            $this->locked = 1;
        }

        return $ok;
    }

    //解除锁定
    public function unlock()
    {
        //记标为解除锁定
        $ok = $this->getTable('Article')->update($this->id, ['locked'=>0]);
        if ($ok) {
            $this->locked = 0;
        }

        return $ok;
    }

    //删除文章
    public function del()
    {
        //标记删除自身数据
        $ok = $this->getTable('Article')->update($this->id, ['deleted'=>1]);
        if ($ok) {
            //记标为删除
            $this->deleted = 1;

            //删除标签列表缓存
            foreach ($this->getTags() as $tag) {
                $this->getCache('TagArticles', $tag->id)->delItem($this->id);
            }

            //删除首页缓存
            $this->getCache('IndexArticles')->delItem($this->id);
        }

        return $ok;
    }


}
