<?php


namespace blog\fileManager\entities;


class Coords
{
    /**
     * @var float
     */
    public $left;
    /**
     * @var float
     */
    public $top;

    public function __construct(float $left = 0, float $top = 0)
    {
        $this->left = $left;
        $this->top = $top;
    }
}