<?php


namespace blog\fileManager\entities;


class Dimension
{
    /**
     * @var int
     */
    public $width;
    /**
     * @var int
     */
    public $height;

    public function __construct(int $width, int $height)
    {
        $this->width = $width;
        $this->height = $height;
    }
}