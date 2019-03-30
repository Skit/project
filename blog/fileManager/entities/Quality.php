<?php


namespace blog\fileManager\entities;


class Quality
{
    /**
     * @var int
     */
    public $quality;
    /**
     * @var float
     */
    public $blur;
    /**
     * @var bool
     */
    public $bestfit;

    public function __construct(int $quality = 85, float $blur = 1, bool $bestfit = false)
    {
        $this->quality = $quality;
        $this->blur = $blur;
        $this->bestfit = $bestfit;
    }
}