<?php
namespace Bybzmt\Blog\Web\Controller\Article;

use Bybzmt\Blog\Web\Controller\Web;
use Bybzmt\Blog\Web\Reverse;
use Bybzmt\Blog\Common\Helper\Pagination;
use Bybzmt\Blog\Web\Helper\Cfg;

class Show extends Web
{
    public $article;
    public $offset;
    public $length = Cfg::COMMENT_LENGTH;
    public $reply_length = Cfg::REPLY_LENGTH;
    public $id;
    public $msg;

    public function init()
    {
        $this->id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $this->page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($this->page < 1) {
            $this->page = 1;
        }

        $this->offset = ($this->page -1) * $this->length;
    }

    public function valid()
    {
        $this->article = $this->_context->getRow('Article', $this->id);
        if ($this->article) {
            return true;
        }

        $this->msg = "文章不存在";

        return false;
    }

    public function fail()
    {
        var_dump($this->msg);
    }

    public function show()
    {
        //文章作者
        $author = $this->_context->getLazyRow("User", $this->article->user_id);

        //文章标签列表
        $tag_rows = $this->article->getTags();
        $taglist = array();
        foreach ($tag_rows as $row) {
            $taglist[] = array(
                'name' => $row->name,
                'url' => Reverse::mkUrl('Article.Lists', ['tag'=>$row->id])
            );
        }

        //文章评论
        $comments = $this->article->getComments($this->offset, $this->length);
        $commentsNum = $this->article->getCommentsNum();

        //过滤掉无效数据交,并标记预加载
        //将实际载加数据向后推迟，实现最终一次查询加载所有数据的效果
        $filter = function($comment){
            if (!$comment || !$comment->id || $comment->status != 1) {
                return false;
            }

            $comment->replys = $comment->getReply(0, $this->reply_length+1);
            if (count($comment->replys) > $this->reply_length) {
                array_pop($comment->replys);

                $comment->replysMore = true;
            } else {
                $comment->replysMore = false;
            }

            //预加载用户
            $comment->user = $this->_context->getLazyRow("User", $comment->user_id);

            return true;
        };

        $filter2 = function($comment){
            if (!$comment || !$comment->id || $comment->status != 1) {
                return false;
            }

            //预加载用户
            $comment->user = $this->_context->getLazyRow("User", $comment->user_id);

            return true;
        };

        $comments_ss = array();
        foreach (array_filter($comments, $filter) as $comment) {
            $replys= array();
            foreach (array_filter($comment->replys, $filter2) as $reply) {
                $replys[] = array(
                    'id' => $reply->id,
                    'content' => $reply->content,
                    'user' => $reply->user,
                    'addtime' => $reply->addtime,
                );
            }

            $comments_ss[] = array(
                'id' => $comment->id,
                'content' => $comment->content,
                'addtime' => $comment->addtime,
                'user' => $comment->user,
                'replys' => $replys,
                'replysMore' => $comment->replysMore,
            );
        }

        //评论分页
        $pagination = Pagination::style1($commentsNum, $this->length, $this->page, function($page){
            $params = array(
                'id' => $this->id,
            );

            if ($page > 1) {
                $params['page'] = $page;
            }

            return Reverse::mkUrl('Article.Show', $params);
        });

        //显示
        $this->render(array(
            'uid' => $this->_uid,
            'taglist' => $taglist,
            'article' => $this->article,
            'author' => $author,
            'comments' => $comments_ss,
            'commentsNum' => $commentsNum,
            'pagination' => $pagination,
        ));
    }


}
