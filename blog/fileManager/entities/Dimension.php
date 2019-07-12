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

    public function __construct(int $width = 0, int $height = 0)
    {
        $this->width = $width;
        $this->height = $height;
    }
}