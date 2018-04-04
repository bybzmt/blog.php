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
        $num = $this->incr(__FUNCTION__);
        if ($num > 100) {
            $this->setLocked(__FUNCTION__);
        }
    }

    //登陆次数
    public function incr_doLogin()
    {
        $num = $this->incr(__FUNCTION__);
        if ($num > 30) {
            $this->setLocked(__FUNCTION__);
        }
    }

    //注册帐号次数
    public function incr_register()
    {
        $num = $this->incr(__FUNCTION__);
        if ($num > 30) {
            $this->setLocked(__FUNCTION__);
        }
    }





}
