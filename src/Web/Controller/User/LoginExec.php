<?php
namespace Bybzmt\Blog\Web\Controller\User;

use Bybzmt\Blog\Web\Controller\Web;
use Bybzmt\Blog\Web\Reverse;

class LoginExec extends Web
{
    public $username;
    public $password;
    public $captcha;
    public $se_captcha;
    public $go;

    public $user;
    public $error;

    public function init()
    {
        session_start();

        $this->username = isset($_POST['username']) ? $_POST['username'] : null;
        $this->password = isset($_POST['password']) ? $_POST['password'] : null;
        $this->go = isset($_POST['go']) ? $_POST['go'] : null;
        $this->captcha = isset($_POST['captcha']) ? $_POST['captcha'] : null;
        $this->se_captcha = isset($_SESSION['captcha']) ? $_SESSION['captcha'] : null;
    }

    public function valid()
    {
        if (!$this->captcha) {
            $this->error = "验证码不能为空";
            return false;
        }

        if (strtoupper($this->captcha) != strtoupper($this->se_captcha)) {
            $this->error = "验证码错误";
            return false;
        }

        if (!$this->username) {
            $this->error = "用户名不能为空";
            return false;
        }

        $this->user = $this->_context->getService("User")->getUser($this->username);
        if (!$this->user) {
            $this->error = "用户名或密码错误";
            return false;
        }

        if (!$this->user->validPass($this->password)) {
            $this->error = "用户名或密码错误";
            return false;
        }

        return true;
    }

    public function fail()
    {
        $go = $this->go ? $this->go : Reverse::mkUrl("Article.Lists");

        $login = Reverse::mkUrl("User.Login", ['go'=>$go, 'msg'=>$this->error]);

        header("Location: $login");
    }

    public function show()
    {
        $_SESSION['uid'] = $this->user->id;

        $go = $this->go ? $this->go : Reverse::mkUrl("Article.Lists");

        header("Location: $go");
        echo ("Location: $go");
    }
}
