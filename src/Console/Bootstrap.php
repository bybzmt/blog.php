<?php
namespace Bybzmt\Blog\Console;

use Bybzmt\Framework\Bootstrap as PBootstrap;

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

        $router->run();
    }


}
