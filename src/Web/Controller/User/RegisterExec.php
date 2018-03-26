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
        $this->username = isset($_POST['username']) ? trim($_POST['username']) : null;
        $this->password = isset($_POST['password']) ? trim($_POST['password']) : null;
        $this->nickname = isset($_POST['nickname']) ? trim($_POST['nickname']) : null;
        $this->captcha = isset($_POST['captcha']) ? trim($_POST['captcha']) : null;
        $this->se_captcha = isset($this->_ctx->session['captcha']) ? $this->_ctx->session['captcha'] : null;

        $this->_ctx->session['captcha'] = null;

        //记录注册调用次数
        $this->_ctx->getService("Security")->incr_doRegister();
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

            //记录验证码错误次数
            $this->_ctx->getService("Security")->incr_captchaError();

            return false;
        }

        if (!$this->username) {
            $this->error = "用户名不能为空";
            return false;
        }

        $user = $this->_ctx->getService("User")->getUser($this->username);
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
        $user = $this->_ctx->getService("User")->addUser($this->username, $this->nickname);
        if ($user) {
            //记录注册成功
            $this->_ctx->getService("Security")->incr_registerSuccess();

            return $user->setPass($this->password);
        }
        $this->error = "注删失败";
        return false;
    }

    public function fail()
    {
        $go = Reverse::mkUrl("User.Register", ['msg'=>$this->error]);

        header("Location: $go");
    }

    public function show()
    {
        $go = Reverse::mkUrl("User.Register", ['msg'=>'注册成功']);

        header("Location: $go");
    }
}
