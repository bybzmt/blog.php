<?php
namespace Bybzmt\Blog\Admin\Controller\Admin\Reg;

use Bybzmt\Blog\Admin\Controller\Json;

class RegisterExec extends Json
{
    //用户名
    private $user;
    //密码
    private $pass;
    //密码
    private $nickname;
    //验证码
    private $captcha;
    //session中验证码
    private $se_captcha;

    public function init()
    {
        $this->user = trim($this->getPost('user'));
        $this->pass = trim($this->getPost('pass'));
        $this->nickname = trim($this->getPost('nickname'));
        $this->captcha = trim($this->getPost('captcha'));
        $this->se_captcha = $this->getHelper("Session")->flash('admin_captcha');

        //注册次数
        $this->getHelper("Security")->incr_register();
    }

    public function valid()
    {
        //验证安全情况
        if ($this->getHelper("Security")->isLocked()) {
            $this->error = "操作过于频繁请明天再试!";
            return false;
        }

        if (!$this->captcha || strtoupper($this->captcha) != strtoupper($this->se_captcha)) {
            $this->ret = 1;
            $this->data = "验证码错误";
            return false;
        }

        if (!$this->nickname) {
            $this->ret = 1;
            $this->data = "昵称不能为空";
            return false;
        }

        $admin_user = $this->getService('Admin')->findUser($this->user);
        if ($admin_user) {
            $this->ret = 1;
            $this->data = "用户己存在。";
            return false;
        }

        return true;
    }

    public function exec()
    {
        return $this->getService('Admin')->register($this->user, $this->pass, $this->nickname);
    }

    public function show()
    {
        $this->ret = 0;
        $this->data = '注册成功';
    }

}
