<?php


namespace blog\fileManager\managers;

use blog\fileManager\entities\ImageFileSetUp;
use blog\fileManager\entities\ImagickResult;
use blog\fileManager\entities\JpegSetUp;
use blog\fileManager\source\Image;
use Imagick;
use ImagickPixel;

/**
 * Class ImagickManager
 *
 * @property ImageFileSetUp $fileSetUp
 * @property Image $image
 *
 * @package blog\fileManager\services
 */
class ImagickManager
{
    private
        $resizeWidth,
        $resizeHeight,
        $fileSetUp,
        $image,
        $imagick;

    public function __construct(Imagick $imagick)
    {
        $this->imagick = $imagick;

        return $this;
    }

    public function setOrigin(Image $image)
    {
        $this->image = $image;
        $this->imagick->readImage($image->tmpPath);

        return $this;
    }

    public function setUpJpeg(JpegSetUp $fileSetUp)
    {
        $this->fileSetUp = $fileSetUp;
        $this->_setFormat($fileSetUp->format);
        $this->_setJpegQuality($fileSetUp);

        return $this;
    }

    public function createNew(string $format, string $with, string $height, string $pixel = 'white')
    {
        $this->imagick->newImage($with, $height, new ImagickPixel($pixel));
        $this->imagick->setImageFormat($format);
        $this->_setNewSizes();
    }

    public function freeResize()
    {
        if($this->image->scale === 'landscape'){
            $resize_width = $this->image->width > $this->fileSetUp->width ? $this->fileSetUp->width : 0;
            $resize_height = 0;
        }
        else{
            $resize_height = $this->image->height > $this->fileSetUp->height ? $this->fileSetUp->height : 0;
            $resize_width = 0;
        }

        if($resize_height || $resize_width) {
            $this->_resize($resize_width, $resize_height, $this->fileSetUp->blur);
        }

        return $this;
    }

    public function save(string $path): bool
    {
        $result = $this->write($path);
        $this->clear();

        return $result;
    }

    public function write(string $path): bool
    {
        return $this->imagick->writeImage($path);
    }

    public function clear(): bool
    {
        return $this->imagick->clear();
    }

    public function strip()
    {
        $profiles = $this->imagick->getImageProfiles("icc", true);
        $this->imagick->stripImage();

        if(! empty($profiles))
            $this->imagick->profileImage("icc", $profiles['icc']);

        return $this;
    }

    public function getHeight()
    {
        return $this->resizeHeight;
    }

    public function getWidth()
    {
        return $this->resizeWidth;
    }

    private function _resize(int $resize_width, int $resize_height, int $blur)
    {
        $this->imagick->resizeImage($resize_width, $resize_height, Imagick::FILTER_LANCZOS, $blur);
        $this->_setNewSizes();
    }

    private function _setNewSizes()
    {
        $this->resizeHeight = $this->imagick->getImageHeight();
        $this->resizeWidth = $this->imagick->getImageWidth();
    }

    private function _setFormat(string $format)
    {
        $this->imagick->setFormat($format);
    }

    private function _setJpegQuality(JpegSetUp $jpegSetUp)
    {
        $this->imagick->setCompression(Imagick::COMPRESSION_JPEG);
        $this->imagick->setImageCompressionQuality($jpegSetUp->quality);
    }
}