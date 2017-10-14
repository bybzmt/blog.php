<?php
namespace Bybzmt\Blog\Utils;

class Config
{
    static protected $data;

    static protected function init()
    {
        if (!self::$data) {
            self::$data = require CONFIG_PATH . '/config.php';
        }
        return self::$data;
    }

    static public function get(string $keys)
    {
        $data = self::init();

        $tmp = $data;
        $keys = explode('.', $keys);

        foreach ($keys as $key) {
            if (!isset($tmp[$key])) {
                return null;
            }
            $tmp = $tmp[$key];
        }

        return $tmp;
    }

}
