<?php /** @noinspection ALL */


namespace blog\fileManager\entities;


class PostImageSetUp extends JpegSetUp
{
    public function __construct(int $width, int $height, int $quality, float $blur)
    {
        parent::__construct($width, $height, $quality, $blur);
    }
}