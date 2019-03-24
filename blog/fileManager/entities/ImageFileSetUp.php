<?php


namespace blog\fileManager\entities;


class ImageFileSetUp
{
    public
        /**
         * @var int
         */
        $blur,
        /**
         * @var string
         */
        $format,
        /**
         * @var int
         */
        $width,
        /**
         * @var int
         */
        $height;

    public function __construct(string $format, int $width, int $height, float $blur)
    {
        $this->blur = $blur;
        $this->format = $format;
        $this->width = $width;
        $this->height = $height;
    }
}