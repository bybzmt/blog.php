<?php
namespace Bybzmt\Blog\Admin\Controller;

use Bybzmt\Blog\Admin;
use Bybzmt\Blog\Common\Helper;

class Admin_Captcha extends Web
{
    public function init()
    {
        session_start();
    }

    public function show()
    {
        //$obj = new Helper\ValidateCode(118, 36);
        $obj = new Helper\CaptchaCode2(118, 36);

        $_SESSION['admin_captcha'] = $obj->getCode();

        $obj->show([0xDD,0xDD,0xDD], [0x99,0x99,0x99]);
    }

}
