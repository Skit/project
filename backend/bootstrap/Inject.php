<?php

namespace backend\bootstrap;

use blog\fileManager\entities\DraftFilesSession;
use blog\fileManager\entities\JpegSetUp;
use blog\fileManager\ImageManager;
use blog\fileManager\managers\ImagickManager;
use blog\fileManager\services\FileService;
use blog\fileManager\transfers\FileTransfer;
use blog\managers\TagsManager;
use blog\services\TagService;
use blog\transfers\TagTransfer;
use Yii;
use yii\base\BootstrapInterface;
use yii\web\Session;

class Inject implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        $container = Yii::$container;

        $container->setSingleton(TagsManager::class, function (): TagsManager
        {
            return new TagsManager(new TagTransfer(), new TagService());
        });

        $container->setSingleton(ImagickManager::class, function (): ImagickManager
        {
            return new ImagickManager(new \Imagick());
        });

        $container->setSingleton(DraftFilesSession::class, function (): DraftFilesSession
        {
            return new DraftFilesSession(new Session());
        });

   /*     $container->setSingleton(ImageManager::class, function () use ($container): ImageManager
        {
            return new ImageManager($container->get(ImagickManager::class),
                new FileTransfer(),
                new JpegSetUp(250,250, 50, 1));
        });*/

        $container->setSingleton(FileService::class, function (): FileService
        {
            return new FileService();
        });
    }
}