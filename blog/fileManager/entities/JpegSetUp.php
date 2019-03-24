<?php


namespace blog\fileManager\entities;


class JpegSetUp extends ImageFileSetUp
{
    public
        /**
         * @var string
         */
        $format = 'jpeg',
        /**
         * @var int
         */
        $quality;

    public function __construct(int $width, int $height, int $quality, float $blur)
    {
        $this->quality = $quality;
        parent::__construct($this->format, $width, $height, $blur);
    }
}