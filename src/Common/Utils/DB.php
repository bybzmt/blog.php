<?php
namespace Bybzmt\Blog\Utils;

use Bybzmt\Blog\Utils\Config;
use PDO;

class DB
{
    private static $_db = [];

    public static function get($name): PDO
    {
        if (!isset(self::$_db[$name])) {
            $cfgs = Config::get("db.{$name}");

            list($dsn, $user, $pass) = $cfgs[mt_rand(0, count($cfgs)-1)];

            $opts = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];

            self::$_db[$name] = new PDO($dsn, $user, $pass, $opts);
        }

        return self::$_db[$name];
    }

}
