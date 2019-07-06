<?php


namespace blog\fileManager\entities\formats;

use ImagickPixel;
use Imagick;

class ImagickPng extends ImagickAbstract
{
    protected $format = 'png';

    /**
     * @param int $width
     * @param int $height
     */
    public function resize(int $width, int $height)
    {
        $this->imagick->setBackgroundColor(new ImagickPixel('white'));
        $this->imagick->resizeImage (
            $width,
            $height,
            $this->imagick::FILTER_LANCZOS,
            $this->setUp->quality->blur,
            $this->setUp->quality->bestfit
        );

        parent::afterChange();
    }

    public function beforeWrite()
    {
        $pngImage = new Imagick();
        $pngImage->newImage($this->imagick->getImageWidth(), $this->imagick->getImageHeight(), "white");
        $pngImage->compositeimage($this->imagick, Imagick::COMPOSITE_OVER, 0, 0);

        $this->imagick = $pngImage;

        parent::beforeWrite();
    }

    public function setQuality()
    {
        $this->imagick->setCompression($this->imagick::COMPRESSION_JPEG);
        $this->imagick->setImageCompressionQuality($this->setUp->quality->value);
    }
}