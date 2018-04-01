<?php
namespace Bybzmt\Blog\Web\Controller\User;

use Bybzmt\Blog\Web\Controller\Web;
use Bybzmt\Blog\Common\Helper;


class Captcha extends Web
{
    public function show()
    {
        //记录验证码生成次数
        $this->getHelper("Security")->incr_showCaptcha();

        $obj = $this->getHelper("Captcha");

        $this->_session['captcha'] = $obj->getCode();

        $image = $obj->show("#dddddd", "#999999");
    }
}
