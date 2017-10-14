<?php
namespace Bybzmt\Blog\Console;

use \Bybzmt\Blog\Router as PRouter;

class Router extends PRouter
{
    protected function _restore()
    {
        //由于我们想在命令行中去编译其它路由数据
        //所以不能在命令行中使用编译的路由据数
        //在这边直接注册路由
        $this->_init();
    }

    protected function _init()
    {
        $this->get('/', ':Help.List');
        $this->get('/compile/routes', ':Compile.Routes');
    }

    protected function default404()
    {
        echo "Command Not Found\n";
    }

}
