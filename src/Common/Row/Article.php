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
        $this->author = $this->getLazyRowCache('User', $row['user_id']);
        $this->_cache_comments_num = (int)$row['cache_comments_num'];
        $this->_cache_tags = $row['cache_tags'];
    }

    public function getCommentsNum()
    {
        return $this->_cache_comments_num;
    }

    public function getComments(int $offset, int $length)
    {
        return $this->getListCache('ArticleComments', $this->id)->gets($offset, $length);

        $comments = [];
        foreach ($ids as $id) {
            $comments[] = $this->getLazyRowCache('Comment', $id);
        }
        return $comments;
    }

    public function getTags()
    {
        $tag_ids = unpack('N*', $this->_cache_tags);

        $tags = [];
        foreach ($tag_ids as $tag_id) {
            $tags[] = $this->getLazyRowCache('Tag', $tag_id);
        }
        return $tags;
    }

    public function addComment(User $user, string $content)
    {
        //保存回复
        $id = $this->getTable('Blog.Comment')->addComment($this->id, $user->id, $user->nickname, $content);

        //修改文章回复数缓存
        $this->getTable('Blog.Article')->addCommentsNum($this->id, 1);
        $this->_cache_comments_num++;

        //添加到列表缓存
        $this->getListCache('ArticleComments', $this->id)->add($id);
        //清除行缓存
        $this->getRowCache('Comment', $id)->del();
    }

    public function setTags(Tags $tags)
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
        $ok = $this->getTable('Blog.ArticleTag')->setTags($this->id, $tag_ids);
        if ($ok) {
            //修改关连表缓存
            $this->getTable('Blog.Article')->setTags($this->id, $tag_sids);

            //修改行缓存
            $this->getRowCache('Article', $this->id)->setTags($tag_ids);

            $this->_cache_tags = $tag_sids;

            return true;
        }
        return false;
    }

    public function publish()
    {
        //修改自身数据
        $this->getTable('Blog.Article')->update($this->id, ['status'=>1]);
        $this->status = 1;

        //更新缓存
        $this->getRowCache('Article')->update(['status'=>1]);

        //添加到首页缓存
        $this->getListCache('IndexArticles')->add($this->id);

        //添加到标签列表缓存
        foreach ($this->tags as $tag) {
            $this->getListCache('TagArticles', $tag->id)->add($this->id);
        }
    }

    public function del()
    {
        //删除标签列表缓存
        foreach ($this->tags as $tag) {
            $this->getListCache('TagArticles', $tag->id)->del($this->id);
        }

        //删除首页缓存
        $this->getListCache('IndexArticles')->add($this->id);

        //删除自身数据
        $this->getTable('Blog.Article')->update($this->id, ['status'=>2]);
        $this->status = 2;

        //缓存标记为删除
        $this->getRowCache('Article')->update($this->id, ['status'=>2]);
    }


}
