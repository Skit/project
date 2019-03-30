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

    public function __construct(float $left, float $top)
    {
        $this->left = $left;
        $this->top = $top;
    }
}