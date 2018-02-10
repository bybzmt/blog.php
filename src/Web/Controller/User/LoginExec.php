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
        $this->username = isset($_POST['username']) ? trim($_POST['username']) : null;
        $this->password = isset($_POST['password']) ? trim($_POST['password']) : null;
        $this->go = isset($_POST['go']) ? $_POST['go'] : null;
        $this->captcha = isset($_POST['captcha']) ? trim($_POST['captcha']) : null;
        $this->se_captcha = isset($_SESSION['captcha']) ? $_SESSION['captcha'] : null;

        $_SESSION['captcha'] = null;

        //记录登陆接口调用次数
        $this->_ctx->getService("Security")->incr_doLogin();
    }

    public function valid()
    {
        //验证安全情况
        if ($this->_ctx->getService("Security")->isLocked()) {
            $this->error = "操作过于频繁请明天再试!";
            return false;
        }

        if (!$this->captcha) {
            $this->error = "验证码不能为空";
            return false;
        }

        if (strtoupper($this->captcha) != strtoupper($this->se_captcha)) {
            $this->error = "验证码错误";

            //记录验证码错
            $this->_ctx->getService("Security")->incr_captchaError();

            return false;
        }

        if (!$this->username) {
            $this->error = "用户名不能为空";
            return false;
        }

        $this->user = $this->_ctx->getService("User")->getUser($this->username);
        if (!$this->user) {
            $this->error = "用户名或密码错误";

            //记录用户名密码出错
            $this->_ctx->getService("Security")->incr_UserOrPassError();

            return false;
        }

        if (!$this->user->validPass($this->password)) {
            $this->error = "用户名或密码错误";

            //记录用户名密码出错
            $this->_ctx->getService("Security")->incr_UserOrPassError();

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
