<?php
namespace Bybzmt\Blog\Web\Controller\User;

use Bybzmt\Blog\Web\Controller\AuthWeb;
use Bybzmt\Blog\Web\Reverse;
use ReflectionObject;
use Bybzmt\Blog\Common\Helper\Pagination;

class Show extends AuthWeb
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
        $this->user = $this->_context->getRow('User', $this->_uid);
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
        list($records, $records_count) = $this->user->getRecords($this->offset, $this->length);

        $record_rows = array();
        foreach ($records as $record) {
            switch ($record->type) {
            case $record::TYPE_COMMENT:
                $comment = $record->getData();
                if (!$comment) {
                    break;
                }

                $article = $comment->getArticle();

                $record_rows[] = array(
                    'type' => $record->type,
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'link' => Reverse::mkUrl("Article.Redirect", ['type'=>$record->type, 'toid'=>$record->to_id]),
                    'article_intro' => $article->intro,
                );
                break;
            case $record::TYPE_REPLY:
                $reply = $record->getData();
                if (!$reply) {
                    break;
                }

                if ($reply->reply_id) {
                    $target = $reply->getReply();
                } else {
                    $target = $reply->getComment();
                }

                $record_rows[] = array(
                    'type' => $record->type,
                    'id' => $reply->id,
                    'content' => $reply->content,
                    'link' => Reverse::mkUrl("Article.Redirect", ['type'=>$record->type, 'toid'=>$record->to_id]),
                    'comment_content' => $target->content,
                );
                break;
            }
        }

        //评论分页
        $pagination = Pagination::style2($records_count, $this->length, $this->page, function($page){
            $params = array();

            if ($page > 1) {
                $params['page'] = $page;
            }

            return Reverse::mkUrl('User.Show', $params);
        });

        $this->render(array(
            'records' => $record_rows,
            'pagination' => $pagination,
        ));
    }


}
