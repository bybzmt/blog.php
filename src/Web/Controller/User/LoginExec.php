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
        $this->username = trim($this->getPost("username"));
        $this->password = trim($this->getPost("password"));
        $this->go = $this->getPost("go");
        $this->captcha = trim($this->getPost("captcha"));
        $this->se_captcha = $this->getHelper("Session")->flash("captcha");

        //记录登陆接口调用次数
        $this->getHelper("Security")->incr_doLogin();
    }

    public function valid()
    {
        //验证安全情况
        if ($this->getHelper("Security")->isLocked()) {
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
            $this->getHelper("Security")->incr_captchaError();

            return false;
        }

        if (!$this->username) {
            $this->error = "用户名不能为空";
            return false;
        }

        $this->user = $this->getService("User")->getUser($this->username);
        if (!$this->user) {
            $this->error = "用户名或密码错误";

            //记录用户名密码出错
            $this->getHelper("Security")->incr_UserOrPassError();

            return false;
        }

        if (!$this->user->validPass($this->password)) {
            $this->error = "用户名或密码错误";

            //记录用户名密码出错
            $this->getHelper("Security")->incr_UserOrPassError();

            return false;
        }

        return true;
    }

    public function fail()
    {
        $go = $this->go ? $this->go : $this->getHelper("Utils")->mkUrl("Article.Lists");

        $login = $this->getHelper("Utils")->mkUrl("User.Login", ['go'=>$go, 'msg'=>$this->error]);

        header("Location: $login");
    }

    public function exec()
    {
        $this->getHelper("Session")->set("uid", $this->user->id);

        return true;
    }

    public function show()
    {
        $go = $this->go ? $this->go : $this->getHelper("Utils")->mkUrl("Article.Lists");

        header("Location: $go");
        echo ("Location: $go");
    }
}
