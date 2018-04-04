<?php
namespace Bybzmt\Blog\Admin\Helper;

use Bybzmt\Framework\Helper;
use ReflectionClass;

/**
 * 将控制跟权限进行映射
 */
class Permissions extends Helper
{
    private $_cache;

    public function getAll()
    {
        if (!$this->_cache) {
            $this->_cache = $this->_getAll();
        }
        return $this->_cache;
    }

    public function reorganize($permissions)
    {
        $table = $this->getTable("AdminPermission");

        $allkey = $this->getAll();

        $about = $table->getAll();
        $about = array_column($about, 'about', 'permission');

        $miss = array();
        $rows = array();
        foreach ($allkey as $key) {
            $group = strstr($key, "_", true);
            if ($group === false) {
                $group = "default";
            }

            if (!isset($about[$key])) {
                $miss[] = $key;
            }

            $rows[$group][] = array(
                'permission' => $key,
                'about' => isset($about[$key]) ? $about[$key] : $key,
                'status' => in_array($key, $permissions),
            );
        }

        //将新添加的入库（方便编辑）
        if ($miss) {
            foreach ($miss as $key) {
                $data = array(
                    'permission' => $key,
                    'about' => $key,
                );
                $table->insert($data);
            }
        }

        //去掉己删除的
        $dels = array_diff(array_keys($about), $allkey);
        if ($dels) {
            foreach ($dels as $key) {
                $table->delete($key);
            }
        }

        return $rows;
    }

    public function _getAll()
    {
        $basedir = __DIR__ . "/../Controller";

        $fun = "";
        $fun = function($dir) use ($basedir, &$fun) {
            $out = array();

            $tmps = scandir($dir);
            foreach ($tmps as $tmp) {
                if ($tmp == "." || $tmp == "..") {
                    continue;
                }

                $file = $dir . "/" . $tmp;

                if (is_dir($file)) {
                    $out = array_merge($out, $fun($file));
                } else {
                    if (preg_match("~\.php$~",$tmp)) {
                        $name = substr($file, strlen($basedir) + 1, -4);

                        $namespace = "Bybzmt\\Blog\\Admin\\Controller\\";
                        $class = $namespace . str_replace("/", "\\", $name);

                        $classAuth1 = $namespace . "AuthWeb";
                        $classAuth2 = $namespace . "AuthJson";

                        $ref = new ReflectionClass($class);
                        if ($ref->isSubclassOf($classAuth1) || $ref->isSubclassOf($classAuth2)) {
                            $key = substr($class, strlen($namespace));
                            $out[] = str_replace("\\", ".", $key);
                        }
                    }
                }
            }

            return $out;
        };

        $out = $fun($basedir);

        return $out;
    }


}
