<?php
namespace Bybzmt\Blog\Admin\Controller\Admin\Reg;

use Bybzmt\Blog\Admin\Controller\Web;

class Captcha extends Web
{
    public function show()
    {
        $this->getHelper("Security")->incr_showCaptcha();

        $obj = $this->getHelper("Captcha");

        $this->getHelper("Session")->set("admin_captcha", $obj->getCode());

        $obj->show("#dddddd", "#999999");
    }

}
