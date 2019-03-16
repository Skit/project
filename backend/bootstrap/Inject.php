<?php

namespace backend\bootstrap;

use blog\managers\TagsManager;
use blog\services\TagService;
use blog\transfers\TagTransfer;
use Yii;
use yii\base\BootstrapInterface;

class Inject implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        $container = Yii::$container;

        $container->setSingleton(TagsManager::class, function (): TagsManager
        {
            return new TagsManager(new TagTransfer(), new TagService());
        });
    }
}