<?php

namespace App\Helpers;

use Illuminate\Support\Traits\Macroable;

class KhmerNumber
{

    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }
    /**
     * @param string $string
     * @return sting
     */
    public static function convert($string)
    {
        $numbers     = array(
            0 => '០',
            1 => '១',
            2 => '២',
            3 => '៣',
            4 => '៤',
            5 => '៥',
            6 => '៦',
            7 => '៧',
            8 => '៨',
            9 => '៩',
        );

        foreach ($numbers as $k => $v) {
            $string = str_replace($k, $v, $string);
        }

        return $string;
    }
}
