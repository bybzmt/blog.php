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
        $this->se_captcha = isset($_SESSION['captcha']) ? $_SESSION['captcha'] : null;

        $_SESSION['captcha'] = null;
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

        $user = $this->_context->getService("User")->getUser($this->username);
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
        $user = $this->_context->getService("User")->addUser($this->username, $this->nickname);
        if ($user) {
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
