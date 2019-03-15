<?php

use yii\base\BaseObject;

function dd(): void
{
    if ($args = func_get_args()) {
        foreach ($args as $arg) {
            var_dump($arg);
        }
    } else {
        var_dump('exit');
    }
    exit;
}

function getClassName($class): string
{
    $class = is_string($class) ? $class : get_class($class);
    return preg_match('~[\w]+$~', $class, $m) ? $m[0] : $class;
}
