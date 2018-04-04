<?php
namespace Bybzmt\Blog\Web\Helper;

use Bybzmt\Framework\Helper\Security as Base;

/**
 * 安全
 */
class Security extends Base
{
    //展示验证码次数
    public function incr_showCaptcha()
    {
        $this->check(__FUNCTION__, 100);
    }

    //验证码错误次数
    public function incr_captchaError()
    {
        $this->check(__FUNCTION__, 20);
    }

    //登陆操作次数
    public function incr_doLogin()
    {
        $this->check(__FUNCTION__, 30);
    }

    //注册用户次数
    public function incr_doRegister()
    {
        $this->check(__FUNCTION__, 30);
    }

    //成功注册用户数量
    public function incr_registerSuccess()
    {
        $this->check(__FUNCTION__, 5);
    }

    //用户名或密码错误次数
    public function incr_UserOrPassError()
    {
        $this->check(__FUNCTION__, 10);
    }

    //发表评论次数
    public function incr_addComment()
    {
        $this->check(__FUNCTION__, 50);
    }

    //发表文章数量
    public function incr_addArticle()
    {
        $this->check(__FUNCTION__, 100);
    }

}
