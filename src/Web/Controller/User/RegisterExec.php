<?php
namespace Bybzmt\Blog\Web\Controller\User;

use Bybzmt\Blog\Web\Controller\Web;
use Bybzmt\Blog\Web\Reverse;

class RegisterExec extends Web
{
    public $username;
    public $password;
    public $nickname;
    public $captcha;
    public $se_captcha;

    public $error;

    public function init()
    {
        $this->username = trim($this->getPost("username"));
        $this->password = trim($this->getPost("password"));
        $this->nickname = trim($this->getPost("nickname"));
        $this->captcha = trim($this->getPost("captcha"));
        $this->se_captcha = $this->getHelper("Session")->flash("captcha");

        //记录注册调用次数
        $this->getHelper("Security")->incr_doRegister();
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

            //记录验证码错误次数
            $this->getHelper("Security")->incr_captchaError();

            return false;
        }

        if (!$this->username) {
            $this->error = "用户名不能为空";
            return false;
        }

        $user = $this->getService("User")->getUser($this->username);
        if ($user) {
            $this->error = "用户名己存在";
            return false;
        }

        if (!$this->password) {
            $this->error = "密码不能为空";
            return false;
        }

        return true;
    }

    public function exec()
    {
        $user = $this->getService("User")->addUser($this->username, $this->nickname);
        if ($user) {
            //记录注册成功
            $this->getHelper("Security")->incr_registerSuccess();

            return $user->setPass($this->password);
        }
        $this->error = "注删失败";
        return false;
    }

    public function fail()
    {
        $go = $this->getHelper("Utils")->mkUrl("User.Register", ['msg'=>$this->error]);

        header("Location: $go");
    }

    public function show()
    {
        $go = $this->getHelper("Utils")->mkUrl("User.Register", ['msg'=>'注册成功']);

        header("Location: $go");
    }
}
