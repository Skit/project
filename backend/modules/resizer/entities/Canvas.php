<?php


namespace backend\modules\resizer\entities;


class Canvas
{
    /**
     * @var int
     */
    public $naturalWidth;
    /**
     * @var int
     */
    public $naturalHeight;
    /**
     * @var int
     */
    public $width;
    /**
     * @var int
     */
    public $height;
    /**
     * @var int
     */
    public $top;
    /**
     * @var int
     */
    public $left;

    /**
     * Canvas constructor.
     * @param float $left
     * @param float $top
     * @param float $width
     * @param float $height
     * @param float $naturalWidth
     * @param float $naturalHeight
     */
    public function __construct(float $left, float $top, float $width, float $height, float $naturalWidth, float $naturalHeight)
    {
        $this->left = $left;
        $this->top = $top;
        $this->width = $width;
        $this->height = $height;
        $this->naturalWidth = $naturalWidth;
        $this->naturalHeight = $naturalHeight;
    }
}