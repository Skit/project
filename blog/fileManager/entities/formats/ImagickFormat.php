<?php


namespace blog\fileManager\entities\formats;


use Imagick;
use yii\db\Exception;

/**
 * Class ImagickFormat
 * @package blog\fileManager\entities\formats
 */
class ImagickFormat
{
    private
        $_imagick,
        $_object;

    public const
        JPG = 'jpg',
        JPEG = 'jpeg',
        PNG = 'png';

    public function __construct(Imagick $imagick)
    {
        $this->_imagick = $imagick;
    }

    public function determine(string $format)
    {
        switch ($format) {
            case self::JPEG:
            case self::JPG:
                $this->_object = new ImagickJpeg($this->_imagick);
                break;
            case self::PNG:
                $this->_object = new ImagickPng($this->_imagick);
                break;
            default:
                throw new Exception("The {$format} is not supports");
        }

        return $this;
    }

    /**
     * @return ImagickAbstract
     */
    public function get()
    {
        return $this->_object;
    }
}