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
        $this->article = $this->_ctx->getRow('Article', $this->id);
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
        $author = $this->_ctx->getLazyRow("User", $this->article->user_id);

        //文章标签列表
        $tags = $this->article->getTags();

        //文章评论
        $comments = $this->article->getComments($this->offset, $this->length);
        $commentsNum = $this->article->getCommentsNum();

        //过滤掉无效数据交,并标记预加载
        //将实际载加数据向后推迟，实现最终一次查询加载所有数据的效果
        $comments = array_filter($comments, function($comment){
            if (!$comment || !$comment->id || $comment->status != 1) {
                return false;
            }

            $comment->replys = $comment->getReplys(0, $this->reply_length+1);
            if (count($comment->replys) > $this->reply_length) {
                array_pop($comment->replys);

                $comment->replysMore = true;
            } else {
                $comment->replysMore = false;
            }

            //预加载用户
            $comment->user = $this->_ctx->getLazyRow("User", $comment->user_id);

            return true;
        });

        //二次过滤,这样子子回复能一次加载所有
        foreach ($comments as $comment) {
            $comment->replys = array_filter($comment->replys, function($reply){
                if (!$reply || !$reply->id || $reply->status != 1) {
                    return false;
                }

                //预加载用户
                $reply->user = $this->_ctx->getLazyRow("User", $reply->user_id);

                return true;
            });
        }

        //显示
        $this->render(array(
            'uid' => $this->_uid,
            'taglist' => $tags,
            'article' => $this->article,
            'author' => $author,
            'comments' => $comments,
            'commentsNum' => $commentsNum,
            'pagination' => $this->pagination($commentsNum),
        ));
    }

    protected function pagination($count)
    {
        //评论分页
        return Pagination::style2($count, $this->length, $this->page, function($page){
            $params = array(
                'id' => $this->id,
            );

            if ($page > 1) {
                $params['page'] = $page;
            }

            return Reverse::mkUrl('Article.Show', $params);
        });
    }


}
