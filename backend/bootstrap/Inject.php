<?php

namespace backend\bootstrap;

use backend\forms\CategoryForm;
use backend\forms\MetaTagsForm;
use backend\forms\PostsForm;
use backend\forms\TagsForm;
use blog\managers\FormsManager;
use Yii;
use yii\base\BootstrapInterface;

class Inject implements BootstrapInterface
{
    public function bootstrap($app): void
    {
 /*       $container = Yii::$container;

        $container->setSingleton(FormsManager::class, function () use ($app): FormsManager
        {
            return (new FormsManager())
                    ->mergeForm(new PostsForm())
                    ->with(new TagsForm(), new MetaTagsForm(), new CategoryForm());
        });


        $container->set(FormsManager::class);
        $container->set('MegaForm', FormsManager::class);*/
    }
}