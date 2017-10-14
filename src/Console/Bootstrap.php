<?php
namespace Bybzmt\Blog\Console;

use Bybzmt\Blog\Bootstrap as PBootstrap;

class Bootstrap extends PBootstrap
{
    public function run()
    {
        $router = new Router();

        // ------ cli特殊处理 --------
        $router->setMethod('GET');

        if (isset($_SERVER['argv'][1])) {
            $router->setUri($_SERVER['argv'][1]);
        }

        $this->_initParams();

        $router->run();
    }

    /*
     * 初史化参数 转换命令行参数到$_GET数组
     * 格式: --key=value
     */
    protected function _initParams()
    {
        for ($i=2; $i<$_SERVER['argc']; $i++) {
            //从命令行取得参数
            $params = array();
            foreach ($_SERVER['argv'] as $arg) {
                if ($arg[0] == "-") {
                    $params[] = trim($arg, '- ');
                }
            }
            parse_str(implode("&", $params), $_GET);
        }
    }
}
