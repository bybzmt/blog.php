<?php
namespace Bybzmt\Blog\Common\Helper;

use Bybzmt\Framework\Helper\Security as Base;

/**
 * 安全
 */
class Security extends Base
{
    //展示验证码次数
    public function incr_showCaptcha()
    {
        $num = $this->incr(__FUNCTION__);
        if ($num > 100) {
            $this->setLocked(__FUNCTION__);
        }
    }

    //验证码错误次数
    public function incr_captchaError()
    {
        $num = $this->incr(__FUNCTION__);
        if ($num > 20) {
            $this->setLocked(__FUNCTION__);
        }
    }

    //登陆操作次数
    public function incr_doLogin()
    {
        $num = $this->incr(__FUNCTION__);
        if ($num > 30) {
            $this->setLocked(__FUNCTION__);
        }
    }

    //注册用户次数
    public function incr_doRegister()
    {
        $num = $this->incr(__FUNCTION__);
        if ($num > 30) {
            $this->setLocked(__FUNCTION__);
        }
    }

    //成功注册用户数量
    public function incr_registerSuccess()
    {
        $num = $this->incr(__FUNCTION__);
        if ($num > 5) {
            $this->setLocked(__FUNCTION__);
        }
    }

    //用户名或密码错误次数
    public function incr_UserOrPassError()
    {
        $num = $this->incr(__FUNCTION__);
        if ($num > 10) {
            $this->setLocked(__FUNCTION__);
        }
    }

    //发表评论次数
    public function incr_addComment()
    {
        $num = $this->incr(__FUNCTION__);
        if ($num > 50) {
            $this->setLocked(__FUNCTION__);
        }
    }

    //发表文章数量
    public function incr_addArticle()
    {
        $num = $this->incr(__FUNCTION__);
        if ($num > 100) {
            $this->setLocked(__FUNCTION__);
        }
    }

    //新会话产生次数
    public function incr_newSession()
    {
        $num = $this->incr(__FUNCTION__);
        if ($num > 100) {
            $this->setLocked(__FUNCTION__);
        }
    }

}
