<?php
namespace Bybzmt\Blog\Admin\Controller\Admin;

use Bybzmt\Blog\Admin\Controller\Json;

class LoginExec extends Json
{
    //用户名
    private $user;
    //密码
    private $pass;
    //验证码
    private $captcha;
    //session中验证码
    private $se_captcha;

    //管理员对象
    private $admin_user;

    public function init()
    {
        $this->user = isset($_POST['user']) ? trim($_POST['user']) : '';
        $this->pass = isset($_POST['pass']) ? trim($_POST['pass']) : '';
        $this->captcha = isset($_POST['captcha']) ? strtoupper(trim($_POST['captcha'])) : '';
        $this->se_captcha = isset($_SESSION['admin_captcha']) ? strtoupper($_SESSION['admin_captcha']) : '';
        $_SESSION['admin_captcha'] = "";
    }

    public function valid()
    {
        //验证ip是否请求过多
        $ip = $_SERVER['REMOTE_ADDR'];
        $security = $this->_ctx->getCache("IPSecurity", "admin.login");
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

        $this->admin_user = $this->_ctx->getService('Admin')->findUser($this->user);
        if (!$this->admin_user) {
            $this->ret = 1;
            $this->data = "用户名或密码错误.";
            return false;
        }

        if (!$this->admin_user->validPass($this->pass)) {
            $this->ret = 1;
            $this->data = "用户名或密码错误.";
            return false;
        }

        return true;
    }

    public function exec()
    {
        $_SESSION['admin_id'] = $this->admin_user->id;
        $_SESSION['admin_isroot'] = $this->admin_user->isroot;
        if (!$this->admin_user->isroot) {
            $_SESSION['admin_permissions'] = $this->admin_user->getPermissions();
        }
        return true;
    }

    public function show()
    {
        $this->ret = 0;
        $this->data = '登陆成功';
    }

}
