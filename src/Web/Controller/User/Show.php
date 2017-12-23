<?php
namespace Bybzmt\Blog\Web\Controller;

use Bybzmt\Blog\Web\Controller;
use Bybzmt\Blog\Web\Reverse;

class User_Show extends Controller
{
    private $id;
    private $user;
    private $msg;

    public function init()
    {
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

    public function run()
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
