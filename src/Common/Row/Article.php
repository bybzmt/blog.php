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
        $id = $this->getTable('Comment')->insert($data);
        if (!$id) {
            return false;
        }

        $data['id'] = $id;
        //清除行缓存
        $this->getCache('RowCache', 'Comment')->set($id, $data);

        //修改文章回复数缓存
        $this->getTable('Article')->addCommentsNum($this->id, 1);
        $this->_cache_comments_num++;

        //添加到列表缓存
        $this->getCache('ArticleComments', $this->id)->addItem($id);
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
        $ok = $this->getTable('ArticleTag')->setTags($this->id, $tag_ids);
        if ($ok) {
            //修改关连缓存
            $this->getTable('Article')->update($this->id, ['cache_tags' => $tag_sids]);

            //修改行缓存
            $this->getCache('RowCache', 'Article')->update($this->id, ['cache_tags' => $tag_sids]);

            //修改当前对像
            $this->_cache_tags = $tag_sids;

            return true;
        }
        return false;
    }

    public function publish()
    {
        //修改自身数据
        $this->getTable('Article')->update($this->id, ['status'=>1]);
        $this->status = 1;

        //更新缓存
        $this->getCache('RowCache', 'Article')->update($this->id, ['status'=>1]);

        //添加到首页缓存
        $this->getCache('IndexArticles')->addItem($this->id);

        //添加到标签列表缓存
        foreach ($this->tags as $tag) {
            $this->getCache('TagArticles', $tag->id)->addItem($this->id);
        }
    }

    public function del()
    {
        //删除标签列表缓存
        foreach ($this->tags as $tag) {
            $this->getCache('TagArticles', $tag->id)->delItem($this->id);
        }

        //删除首页缓存
        $this->getCache('IndexArticles')->delItem($this->id);

        //标记删除自身数据
        $this->getTable('Article')->update($this->id, ['status'=>2]);
        $this->status = 2;

        //缓存标记为删除
        $this->getCache('RowCache', 'Article')->update($this->id, ['status'=>2]);
    }


}
