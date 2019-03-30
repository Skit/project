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
         * @var string
         */
        $format;

    public function __construct(string $format, Dimension $dimension, Quality $quality)
    {
        $this->format = $format;
        $this->dimension = $dimension;
        $this->quality = $quality;
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