<?php


namespace blog\fileManager\entities;


class ImagickSetUp
{
    public
        /**
         * @var Coords
         */
        $coords,
        /**
         * @var Quality
         */
        $quality,
        /**
         * @var Dimension
         */
        $dimension,
        /**
         * Target format
         * @var string
         */
        $format,
        /**
         * @var bool
         */
        $strip;

    public function __construct(string $format, Dimension $dimension, Quality $quality, Coords $coords, bool $strip)
    {
        $this->format = $format;
        $this->dimension = $dimension;
        $this->quality = $quality;
        $this->strip = $strip;
        $this->coords = $coords;
    }

    public function setDimension(Dimension $dimension)
    {
        $this->dimension = $dimension;
    }

    public function setCoords(Coords $coords)
    {
        $this->coords = $coords;
    }
}