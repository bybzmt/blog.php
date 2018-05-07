<?php
namespace Bybzmt\Blog\Api\GraphQL;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\Type;

class Types
{
    private static $types;

    public static function get($name)
    {
        if (!isset(self::$types[$name])) {
            $class = __NAMESPACE__ . "\\Type\\" . $name."Type";
            self::$types[$name] = new $class();
        }

        return self::$types[$name];
    }

    public static function boolean()
    {
        return Type::boolean();
    }

    public static function float()
    {
        return Type::float();
    }

    public static function id()
    {
        return Type::id();
    }

    public static function int()
    {
        return Type::int();
    }

    public static function string()
    {
        return Type::string();
    }

    public static function listOf($type)
    {
        return new ListOfType($type);
    }

    public static function nonNull($type)
    {
        return new NonNull($type);
    }
}
