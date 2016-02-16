<?php
/**
 * Created by silentium
 * Date: 17.02.16
 * Time: 0:27
 */

namespace Runner;

class Helper
{
    public static function camel2id($name, $separator = '-', $strict = false)
    {
        $regex = $strict ? '/[A-Z]/' : '/(?<![A-Z])[A-Z]/';
        if ($separator === '_') {
            return trim(strtolower(preg_replace($regex, '_\0', $name)), '_');
        } else {
            return trim(strtolower(str_replace(
                '_',
                $separator,
                preg_replace($regex, $separator . '\0', $name)
            )), $separator);
        }
    }

    public static function id2camel($id, $separator = '-')
    {
        return str_replace(' ', '', ucwords(implode(' ', explode($separator, $id))));
    }
}
