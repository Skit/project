<?php


namespace blog\fileManager\entities;


class Quality
{
    /**
     * @var int
     */
    public $value;
    /**
     * @var float
     */
    public $blur;
    /**
     * @var bool
     */
    public $bestfit;

    public function __construct(int $value, float $blur = 1, bool $bestfit = false)
    {
        $this->value = $value;
        $this->blur = $blur;
        $this->bestfit = $bestfit;
    }
}