<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 16.11.2018
 * Time: 8:46
 */

namespace common\helpers;

use Yii;
use yii\helpers\BaseFileHelper;

class FileHelper extends BaseFileHelper
{
    /**
     * @param string $path
     * @param int $mode
     * @param bool $recursive
     * @return bool
     * @throws \yii\base\Exception
     */
    public static function createDirectory($path, $mode = 0775, $recursive = true){

        if (!is_dir($path)) {
            if (!mkdir($path, $mode, $recursive)) {
                throw new \yii\base\Exception("Failed to create directory \"$path\": ");
            }
        }

        return true;
    }

    /**
     * @param string $string
     * @param array $replaceData
     * @param int $gerAlias
     * @return string
     */
    public static function replacer(string $string, array $replaceData = [], int $gerAlias = 1): string
    {
        if (preg_match_all('~({:?[^\/\\\]+})~U', $string, $pathMatches) > 0) {
            if (empty($replaceData)) {
                $data = array_combine($pathMatches[0], array_fill(0, count($pathMatches[0]), null));
            } else {
                foreach ($pathMatches[0] as $key => $pattern) {
                    if (array_key_exists($pattern, $replaceData)) {
                        $data[$pattern] = $replaceData[$pattern];
                    } else {
                        $data[$pattern] = (current($replaceData) === false) ? null : current($replaceData);
                    }
                    next($replaceData);
                }
            }

            foreach ($data as $pattern => $value){
                if (preg_match('~^{([^:]+)}$~', $pattern, $static)) {
                    switch ($static[1]) {
                        case 'salt':
                            $saltData = is_null($value) ?  time() : $value;
                            $replacement[$pattern] = substr(md5($saltData), 0, 6);
                            break;
                        default:
                            if (is_null($value)) {
                                throw new \RuntimeException('Undefined variable: ' . $pattern);
                            }
                            $replacement[$pattern] = $value;
                            break;
                    }
                } elseif (preg_match('~^(?:{:)(.+)}$~', $pattern, $func)) {
                    switch ($func[1]) {
                        case 'date':
                            if (is_null($value)) {
                                $value = 'Y/m';
                            }
                            $replacement[$pattern] = date($value);
                            break;
                        case 'time':
                            $replacement[$pattern] = time();
                            break;
                        case 'ifExist':
                            // TODO добавить проверку существования файла, если есть, прибавить _1, если есть _2 ....
                            $replacement[$pattern] = '';
                            break;
                        default:
                            throw new \RuntimeException('Undefined function: ' . $pattern);
                            break;
                    }
                }
            }
            $string = str_replace(array_keys($replacement), array_values($replacement), $string);
        }

        return $gerAlias ? Yii::getAlias($string) : $string;
    }

}