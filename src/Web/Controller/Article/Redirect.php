<?php
namespace Bybzmt\Blog\Web\Controller\Article;

use Bybzmt\Blog\Web\Controller\Web;
use Bybzmt\Blog\Web\Reverse;
use Bybzmt\Blog\Web\Helper\Cfg;
use Bybzmt\Blog\Common\Row\Record;

class Redirect extends Web
{
    public $type;
    public $to_id;
    public $msg;
    public $url;

    public function init()
    {
        $this->type = isset($_REQUEST['type']) ? (int)$_REQUEST['type'] : 0;
        $this->to_id = isset($_REQUEST['toid']) ? $_REQUEST['toid'] : 0;
    }

    public function valid()
    {
        switch($this->type) {
        case Record::TYPE_COMMENT:
            $comment = $this->_context->getRow("Comment", $this->to_id);
            if (!$comment) {
                $this->msg = "目标错误";
                return false;
            }

            $page = $comment->getCurrentPage(Cfg::COMMENT_LENGTH);

            $this->url = Reverse::mkUrl("Article.Show", ['id'=>$this->article_id, 'page'=>$page]);
            $this->url .= "#comment-". $this->comment->id;

            return true;
        case Record::TYPE_REPLY:
            $reply = $this->_context->getRow("Reply", $this->to_id);
            if (!$reply) {
                $this->msg = "目标错误";
                return false;
            }

            $parent = $this->_context->getRow("Comment", $reply->article_id.":".$reply->comment_id);
            $page = $parent->getCurrentPage(Cfg::COMMENT_LENGTH);

            $page2 = $reply->getCurrentPage(Cfg::REPLY_LENGTH);

            $this->url = Reverse::mkUrl("Article.Show", ['id'=>$reply->article_id, 'page'=>$page]);
            $this->url .= "#torid=".$reply->comment_id.":".$page2.":".$reply->id;

            return true;
        }

        return false;
    }

    public function fail()
    {
        echo $this->msg;
    }

    public function show()
    {
        header("Location: {$this->url}");
    }


}
