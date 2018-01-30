<?php
namespace Bybzmt\Blog\Web\Controller\User;

use Bybzmt\Blog\Web\Controller\Web;
use Bybzmt\Blog\Common\Helper;


class Captcha extends Web
{
    public function show()
    {
        //记录验证码生成次数
        $this->_context->getService("Security")->incr_showCaptcha();

        $obj = new Helper\CaptchaCode(118, 36);

        $_SESSION['captcha'] = $obj->getCode();

        $obj->show([0xDD,0xDD,0xDD], [0x99,0x99,0x99]);
    }
}
