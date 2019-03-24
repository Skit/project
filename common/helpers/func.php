<?php

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

function da(): void
{
    if ($args = func_get_args()) {
        foreach ($args as $arg) {
            print_r($arg);
        }
    } else {
        print_r('exit');
    }
    exit;
}

function getClassName($class): string
{
    $class = is_string($class) ? $class : get_class($class);
    return preg_match('~[\w]+$~', $class, $m) ? $m[0] : $class;
}

// TODO вынести в отдельный класс
function keyboardTranslate($letter)
{
    return strtr($letter, [
        'q' => 'й',   '\'' => 'э',  'ф' => 'a',
        'w' => 'ц',   'z' => 'я',   'ы' => 's',
        'e' => 'у',   'x' => 'ч',   'в' => 'd',
        'r' => 'к',   'c' => 'с',   'а' => 'f',
        't' => 'е',   'v' => 'м',   'п' => 'g',
        'y' => 'н',   'b' => 'и',   'р' => 'h',
        'u' => 'г',   'n' => 'т',   'о' => 'j',
        'i' => 'ш',   'm' => 'ь',   'л' => 'k',
        'o' => 'щ',   ',' => 'б',   'д' => 'l',
        'p' => 'з',   '.' => 'ю',   'ж' => ';',
        '[' => 'х',   'й' => 'q',   'э' => '\'',
        ']' => 'ъ',   'ц' => 'w',   'я' => 'z',
        'a' => 'ф',   'у' => 'e',   'ч' => 'x',
        's' => 'ы',   'к' => 'r',   'с' => 'c',
        'd' => 'в',   'е' => 't',   'м' => 'v',
        'f' => 'а',   'н' => 'y',   'и' => 'b',
        'g' => 'п',   'г' => 'u',   'т' => 'n',
        'h' => 'р',   'ш' => 'i',   'ь' => 'm',
        'j' => 'о',   'щ' => 'o',   'б' => ',',
        'k' => 'л',   'з' => 'p',   'ю' => '.',
        'l' => 'д',   'х' => '[',
        ';' => 'ж',   'ъ' => ']',
    ]);
}
