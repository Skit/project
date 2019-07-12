<?php


namespace blog\fileManager\managers;

use blog\fileManager\entities\formats\ImagickAbstract;
use blog\fileManager\entities\formats\ImagickFormat;
use blog\fileManager\entities\ImagickResult;
use blog\fileManager\entities\ImagickSetUp;
use blog\fileManager\source\Image;

/**
 * Class ImagickManager
 *
 * @property ImagickSetUp $setUp
 * @property Image $image
 * @property ImagickAbstract $format
 * @property ImagickFormat $formatObject
 * @property ImagickResult $result
 *
 * @package blog\fileManager\services
 */
class ImagickManager
{
    private
        $result,
        $setUp,
        $image,
        $formatObject,
        $format;

    /**
     * ImagickManager constructor.
     * @param ImagickFormat $imagickFormat
     * @param ImagickResult $imagickResult
     */
    public function __construct(ImagickFormat $imagickFormat, ImagickResult $imagickResult)
    {
        $this->formatObject = $imagickFormat;
        $this->result = $imagickResult;
    }

    /**
     * @param ImagickSetUp $imagickSetUp
     * @param Image $image
     * @return $this
     * @throws \ImagickException
     * @throws \yii\db\Exception
     */
    public function init(ImagickSetUp $imagickSetUp, Image $image)
    {
        $this->setUp = $imagickSetUp;
        $this->image = $image;
        $this->formatObject->determine($image->extension);

        $this->format = $this->formatObject->get();
        $this->format->readImage($image->tmpPath);
        $this->format->setSetUp($this->setUp);

        return $this;
    }

    /**
     * @return ImagickResult
     */
    public function getResult(): ImagickResult
    {
        return $this->result;
    }

    /**
     * @param string $format
     * @param string $with
     * @param string $height
     */
    public function createNew(string $format, string $with, string $height)
    {
        // FIXME test uses this method
        $this->format->createNew($with, $height);
        //$this->imagick->setImageFormat($format);
    }

    public function freeResize(): self
    {
        if ($this->image->scale === 'landscape') {
            $resize_width = $this->image->width > $this->setUp->dimension->width ? $this->setUp->dimension->width : 0;
            $resize_height = 0;
        }
        else {
            $resize_height = $this->image->height > $this->setUp->dimension->height ? $this->setUp->dimension->height : 0;
            $resize_width = 0;
        }

        if ($resize_height || $resize_width) {
            $this->format->resize($resize_width, $resize_height);
        }
        $this->_setNewSize();

        return $this;
    }

    public function resize(): self
    {
        $this->format->resize($this->setUp->dimension->width, $this->setUp->dimension->height);
        $this->_setNewSize();

        return $this;
    }

    public function crop(): self
    {
        $this->format->crop($this->setUp->dimension->width, $this->setUp->dimension->height,
            -$this->setUp->coords->left, -$this->setUp->coords->top);
        $this->_setNewSize();

        return $this;
    }

    /**
     * @param string $path
     * @return ImagickManager
     * @throws \yii\base\Exception
     */
    public function save(string $path): self
    {
        $this->result->setResult($this->format->write($path));
        return $this;
    }

    private function _setNewSize(): void
    {
        $this->result->setNewWidth($this->format->getWidth());
        $this->result->setNewHeight($this->format->getHeight());
    }
}