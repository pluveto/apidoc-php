<?php

namespace ApiDoc\Util;

class ArrayHelper
{

    /**
     * 如果存在 key 就返回对应 value, 否则返回默认值
     *
     * @param array $array
     * @param string $key
     * @param object $default
     * @return mixed
     */
    public static function getWhenHasKey($array, $key, $default)
    {
        return array_key_exists($key, $array) ? $array[$key] : $default;
    }

    public static function countEmpty($array)
    {
        $ret = 0;
        $size = count($array);
        for ($i = 0; $i < $size; $i++) {
            $ret += empty($array[$i]) ? 1 : 0;
        }
        return $ret;
    }
}
