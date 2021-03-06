<?php
namespace Bybzmt\Blog\Web\Controller\User;

use Bybzmt\Blog\Web\Controller\Web;


class Captcha extends Web
{
    public function show()
    {
        //记录验证码生成次数
        $this->getHelper("Security")->incr_showCaptcha();

        $obj = $this->getHelper("Captcha");

        $this->getHelper("Session")->set("captcha", $obj->getCode());

        $obj->show("#dddddd", "#999999");
    }
}
