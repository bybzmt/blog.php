<?php
namespace Bybzmt\Blog\Admin\Helper;

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

    //登陆次数
    public function incr_doLogin()
    {
        $this->check(__FUNCTION__, 30);
    }

    //注册帐号次数
    public function incr_register()
    {
        $this->check(__FUNCTION__, 30);
    }





}
