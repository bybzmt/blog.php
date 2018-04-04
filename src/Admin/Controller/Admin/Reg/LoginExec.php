<?php
namespace Bybzmt\Blog\Admin\Controller\Admin\Reg;

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
        $this->user = trim($this->getPost("user"));
        $this->pass = trim($this->getPost("pass"));
        $this->captcha = trim($this->getPost('captcha'));
        $this->se_captcha = $this->getHelper("Session")->flash('admin_captcha');

        //记录登陆接口调用次数
        $this->getHelper("Security")->incr_doLogin();
    }

    public function valid()
    {
        //验证安全情况
        if ($this->getHelper("Security")->isLocked()) {
            $this->data = "操作过于频繁请明天再试!";
            return false;
        }

        if (!$this->captcha || strtoupper($this->captcha) != strtoupper($this->se_captcha)) {
            $this->ret = 1;
            $this->data = "验证码错误";
            return false;
        }

        $this->admin_user = $this->getService('Admin')->findUser($this->user);
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
        $session = $this->getHelper("Session");
        $session['admin_id'] = $this->admin_user->id;
        $session['admin_isroot'] = $this->admin_user->isroot;
        if (!$this->admin_user->isroot) {
            $session['admin_permissions'] = $this->admin_user->getPermissions();
        }
        return true;
    }

    public function show()
    {
        $this->ret = 0;
        $this->data = '登陆成功';
    }

}
