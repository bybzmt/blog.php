<?php
namespace Bybzmt\Blog\Admin\Controller\Admin;

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
        $this->user = isset($_POST['user']) ? trim($_POST['user']) : '';
        $this->pass = isset($_POST['pass']) ? trim($_POST['pass']) : '';
        $this->nickname = isset($_POST['nickname']) ? trim($_POST['nickname']) : '';
        $this->captcha = isset($_POST['captcha']) ? strtoupper(trim($_POST['captcha'])) : '';
        $this->se_captcha = isset($_SESSION['admin_captcha']) ? strtoupper($_SESSION['admin_captcha']) : '';
        $_SESSION['admin_captcha'] = "";
    }

    public function valid()
    {
        //验证ip是否请求过多
        $ip = $_SERVER['REMOTE_ADDR'];
        $security = $this->_context->getCache("IPSecurity", "admin.login");
        if (!$security->check($ip)) {
            $this->ret = 1;
            $this->data = "检测到安全风险请稍后再试。";
            return false;
        }

        if (!$this->captcha || $this->captcha != $this->se_captcha) {
            $this->ret = 1;
            $this->data = "验证码错误";
            return false;
        }

        if (!$this->nickname) {
            $this->ret = 1;
            $this->data = "昵称不能为空";
            return false;
        }

        $admin_user = $this->_context->getService('Admin')->findUser($this->user);
        if ($admin_user) {
            $this->ret = 1;
            $this->data = "用户己存在。";
            return false;
        }

        return true;
    }

    public function exec()
    {
        return $this->_context->getService('Admin')->register($this->user, $this->pass, $this->nickname);
    }

    public function show()
    {
        $this->ret = 0;
        $this->data = '注册成功';
    }

}
