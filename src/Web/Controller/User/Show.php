<?php
namespace Bybzmt\Blog\Web\Controller\User;

use Bybzmt\Blog\Web\Controller\AuthWeb;
use Bybzmt\Blog\Web\Reverse;

class Show extends AuthWeb
{
    private $id;
    private $user;
    private $msg;

    public function init()
    {
        $this->_uid = isset($_SESSION['uid']) ? (int)$_SESSION['uid'] : 0;
        $this->id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    }

    public function valid()
    {
        $this->user = $this->_context->getService('User')->getUser($this->id);
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
        var_dump($this->user);
        die;

        $article = array(
            'title' => $this->article->title,
            'content' => $this->article->content,
            'addtime' => $this->article->addtime,
            'edittime' => $this->article->edittime,
            'author_nickname' => $this->article->author->nickname,
            'author_id' => $this->article->author->id,
        );

        $data = [
            'article' => $article,
        ];

        $this->render($data);
    }


}
