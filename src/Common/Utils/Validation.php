<?php
namespace Bybzmt\Blog\Utils;

class Validation
{
    public static function str(string $type, string $data)
    {
        $len = PHP_INT_MAX;

        if (($idx = strpos($type, ':')) !== false) {
            list($type, $len) = explode(':', $type);
        }

        switch ($type)
        {
        case 'uint8': return ctype_digit($data) && $data <= 255;
        case 'uint16': return ctype_digit($data) && $data <= 65535;
        case 'uint32': return ctype_digit($data) && $data <= 4294967296;
        case 'uint64': return ctype_digit($data);
        case 'int8': return (((string)(int)$data) === $data) && -127 <= $data && $data <= 127;
        case 'int16': return (((string)(int)$data) === $data) && -32767 <= $data && $data <= 32767;
        case 'int32': return (((string)(int)$data) === $data) && -2147516414 <= $data && $data <= 2147516414;
        case 'int64': return (((string)(int)$data) === $data);
        case 'char' : return strlen($data) <= $len;
        case 'string' : return mb_strlen($data) <= $len;
        case 'timestamp' : return strtotime($data) > 0;
        }

        throw new Exception("Validation type:$type not defined.");
    }
}
