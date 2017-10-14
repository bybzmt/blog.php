<?php
namespace Bybzmt\Blog\Console\Controller;

use Bybzmt\Blog\Console\Controller;

/**
 * 编译器
 */
class Compile_Routes extends Controller
{
    /**
     * 编译所有模块路由数据
     */
    public function run()
    {
        $names = implode('\\', array_slice(explode('\\', __NAMESPACE__), 0, -3));

        $base = __DIR__ . '/../../../';

        $dirs = scandir($base);
        foreach ($dirs as $dir) {
            if ($dir == '.' || $dir == '..') {
                continue;
            }
            if (is_dir($base . $dir)) {
                $class = $names . '\\' . $dir . "\\Router";
                if (class_exists($class)) {
                    $this->writeFile(new $class());
                }
            }
        }

        echo "over.\n";
    }

    private function writeFile($router)
    {
        $dir = ASSETS_PATH . '/compiled/';
        $prefix = str_replace('\\', '_', get_class($router));

       //路由数据缓存文件
        $file_routes = $dir . $prefix . "_routes.php";
        //反向路由数据缓存文件
        $file_reverse = $dir . $prefix . "_reverse.php";

        //编译路由数据
        $tool = new \Bybzmt\Router\Tool($router->getRoutes());
        $code_routes = $tool->exportRoutes();
        $code_reverse = $tool->exportReverse();

        //写缓存文件
        file_put_contents($file_routes, $code_routes);
        file_put_contents($file_reverse, $code_reverse);
    }

}
