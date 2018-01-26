<?php
namespace Bybzmt\Blog\Admin\Controller\Admin;

use Bybzmt\Blog\Admin\Controller\Web;
use Bybzmt\Blog\Common\Helper;

class Captcha extends Web
{
    public function init()
    {
        session_start();
    }

    public function show()
    {
        $obj = new Helper\CaptchaCode(118, 36);

        $_SESSION['admin_captcha'] = $obj->getCode();

        $obj->show([0xDD,0xDD,0xDD], [0x99,0x99,0x99]);
    }

}
