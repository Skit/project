<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 14.11.2018
 * Time: 20:47
 */

namespace backend\modules\resizer\models;

use Imagick;
use yii\base\Model;

class Resizer extends Model
{
    /**
     * @param $file
     * @return Imagick
     * @throws \ImagickException
     */
    public static function imagick($file) : Imagick
    {
        return new Imagick($file);
    }

    /**
     * @param Imagick $imagick
     * @param int $width
     * @param int $height
     * @param float $blur
     * @param bool $bestfit
     * @return bool
     */
    public static function resize(Imagick $imagick, int $width, int $height, float $blur, bool $bestfit): bool
    {
        return $imagick->resizeImage($width, $height, Imagick::FILTER_LANCZOS, $blur, $bestfit);
    }

    /**
     * @param Imagick $imagick
     * @param int $width
     * @param int $height
     * @param int $left
     * @param int $top
     * @return bool
     */
    public static function crop(Imagick $imagick, int $width, int $height, int $left, int $top): bool
    {
         return $imagick->cropImage($width, $height, $left, $top);
    }

    /**
     * @param Imagick $imagick
     * @param $path
     * @return bool
     */
    public static function save(\Imagick $imagick, $path): bool
    {
        return $imagick->writeImage($path);
    }

    /**
     * @param Imagick $imagick
     * @return bool
     */
    public function end(\Imagick $imagick): bool
    {
        $imagick->destroy();
    }
}