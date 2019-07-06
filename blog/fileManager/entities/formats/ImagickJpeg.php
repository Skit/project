<?php


namespace blog\fileManager\entities\formats;


class ImagickJpeg extends ImagickAbstract
{
    protected $format = 'jpeg';

    /**
     *
     */
    public function setQuality()
    {
        $this->imagick->setCompression($this->imagick::COMPRESSION_JPEG);
        $this->imagick->setImageCompressionQuality($this->setUp->quality->value);
    }

    /**
     * @param int $width
     * @param int $height
     */
    public function resize(int $width, int $height)
    {
        $this->imagick->resizeImage (
            $width,
            $height,
            $this->imagick::FILTER_LANCZOS,
            $this->setUp->quality->blur,
            $this->setUp->quality->bestfit
        );

        parent::afterChange();
    }
}