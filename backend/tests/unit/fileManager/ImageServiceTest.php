<?php


namespace backend\tests\unit\fileManager;

use blog\fileManager\managers\ImagickManager;
use Yii;
use blog\fileManager\entities\JpegSetUp;
use blog\fileManager\source\Image;
use Codeception\Test\Unit;

/**
 * Class ImageServiceTest
 *
 * @property \blog\fileManager\managers\ImagickManager $service
 * @package backend\tests\unit\fileManager
 */
class ImageServiceTest extends Unit
{
    public $service;

    public function _before()
    {
        $this->service = Yii::$container->get(ImagickManager::class);
        parent::_before();
    }

    public function testCreateImage()
    {
        $this->service->createNew('jpg',640, 480, 'white');
        $this->service->clear();

        expect($this->service->getHeight())->equals(480);
        expect($this->service->getWidth())->equals(640);
    }

    public function testFreeResize()
    {
        $file = codecept_data_dir('image/727x522.jpg');
        $resize = $this->service
            ->setOrigin(new Image('testFile', $file, 100500, 'image/jpeg'))
            ->setUpJpeg(new JpegSetUp(640, 480, 85, 1))
            ->freeResize();
        $resize->clear();

        expect($resize->getHeight())->equals(460);
        expect($resize->getWidth())->equals(640);
    }

}