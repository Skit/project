<?php


namespace blog\fileManager\entities\formats;

use blog\fileManager\entities\ImagickSetUp;
use blog\fileManager\entities\Quality;
use Imagick;
use ImagickPixel;
use yii\base\Exception;

/**
 * Class ImagickAbstract
 * @package blog\fileManager\entities\formats
 * @property ImagickSetUp $setUp
 */
Abstract class ImagickAbstract
{
    protected
        $format,
        $setUp,
        $imagick;

    private
        $width,
        $height;

    public function __construct(Imagick $imagick)
    {
        $this->imagick = $imagick;
        $this->_setFormat();
    }

    /**
     * @param ImagickSetUp $imagickSetUp
     */
    public function setSetUp(ImagickSetUp $imagickSetUp)
    {
        $this->setUp = $imagickSetUp;
    }

    /**
     * @param string $path
     * @throws \ImagickException
     */
    public function readImage(string $path)
    {
        $this->imagick->readImage($path);
    }

    /**
     * @param $with
     * @param $height
     * @param string $pixel
     */
    public function createNew($with, $height, string $pixel = 'white')
    {
        $this->imagick->newImage($with, $height, new ImagickPixel($pixel));
    }

    public function getWidth()
    {
        if(! $this->width) {
            $this->width = $this->imagick->getImageWidth();
        }
        return $this->width;
    }

    public function getHeight()
    {
        if(! $this->height) {
            $this->height = $this->imagick->getImageHeight();
        }
        return $this->height;
    }

    public function write(string $path)
    {
        $this->beforeWrite();
        if (! $result = $this->imagick->writeImage($path)) {
            throw new Exception("Can't save image to {$path}");
        }
        $this->afterWrite();

        return $result;
    }

    /**
     * @param int $width
     * @param int $height
     */
    public function resize(int $width, int $height)
    {
        $this->afterChange();
    }

    /**
     * @param int $width
     * @param int $height
     * @param int $x
     * @param int $y
     */
    public function crop(int $width, int $height, int $x, int $y)
    {
        $this->imagick->cropImage($width, $height, $x, $y);
        $this->afterChange();
    }

    /**
     * @param Quality $quality
     */
    public function setQuality() {}

    protected function beforeWrite()
    {
        $this->setQuality();
        $this->imagick->setImageFormat($this->format);

        if ($this->setUp->strip) {
            $this->strip();
        }
    }

    protected function afterWrite()
    {
        $this->imagick->clear();
    }

    protected function afterChange()
    {
        $this->width = $this->getWidth();
        $this->height = $this->getHeight();
    }

    /**
     * @return $this
     */
    protected function strip()
    {
        $profiles = $this->imagick->getImageProfiles("icc", true);
        $this->imagick->stripImage();

        if (! empty($profiles))
            $this->imagick->profileImage("icc", $profiles['icc']);

        return $this;
    }

    private function _setFormat()
    {
        $this->imagick->setFormat($this->format);
    }
}