<?php
namespace Bybzmt\Blog\Admin\Controller;

use Bybzmt\Blog\Common;
use Bybzmt\Blog\Admin;

abstract class Base extends Common\Controller
{
    public function __construct($context)
    {
        parent::__construct($context);

        session_start();
    }
}
