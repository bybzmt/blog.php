<?php
namespace Bybzmt\Blog\Web\Controller\User;

use Bybzmt\Blog\Web\Controller\AuthWeb;
use Bybzmt\Blog\Web\Reverse;

class ArticleList extends AuthWeb
{
    protected $length = 10;
    protected $offset;
    protected $user;
    protected $msg;
    protected $page;

    public function init()
    {
        $this->page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($this->page < 1) {
            $this->page = 1;
        }

        $this->offset = ($this->page - 1) * $this->length;
    }

    public function valid()
    {
        $this->user = $this->_ctx->getRow('User', $this->_uid);
        if ($this->user) {
            return true;
        }

        $this->msg = "用户不存在";

        return false;
    }

    public function fail()
    {
        var_dump($this->msg);
    }

    public function show()
    {
        $service = $this->get("Service.Article");

        $articles = $service->getUserList($this->user, $this->offset, $this->length);
        $count = $service->getUserListCount($this->user);

        //预加载用户
        array_walk($articles, function(&$row){
            $row->author = $this->_ctx->getLazyRow("User", $row->user_id);
            $row->commentsNum = $row->getCommentsNum();
            $row->tags = $row->getTags();
            $row->link = $this->_ctx->get("Reverse")->mkUrl('Article.Show', ['id'=>$row->id]);
        });

        $this->render(array(
            'articles' => $articles,
            'pagination' => $this->pagination($count),
        ));
    }

    protected function pagination($count)
    {
        //评论分页
        return $this->_ctx->get("Helper.Pagination")->style2($count, $this->length, $this->page, function($page){
            $params = array();

            if ($page > 1) {
                $params['page'] = $page;
            }

            return $this->_ctx->get("Reverse")->mkUrl('User.Show', $params);
        });
    }


}
