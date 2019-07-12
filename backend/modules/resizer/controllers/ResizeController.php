<?php

namespace backend\modules\resizer\controllers;

use backend\modules\resizer\forms\ImageForm;
use blog\fileManager\entities\Coords;
use blog\fileManager\entities\Dimension;
use blog\fileManager\entities\ImagickSetUp;
use blog\fileManager\ImageManager;
use blog\fileManager\source\Image;
use Yii;
use yii\base\Module;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Request;

/**
 * Default controller for the `translator` module
 */
class ResizeController extends Controller
{
    public $service;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var ImageManager
     */
    private $imageManager;

    public function __construct(string $id, Module $module, ImageManager $imageManager, Request $request, array $config = [])
    {
        $this->request = $request;
        $this->imageManager = $imageManager;
        parent::__construct($id, $module, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'cropper' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionCropper()
    {
        $form = new ImageForm();
        // FIXME BLOG-15
        $formDataHack = [$form->formName() => $this->request->post()];
        if ($this->request->isAjax && $form->load($formDataHack) && $form->validate()) {
            /* @var ImagickSetUp $setUp*/
            $setUp = Yii::$app->params['resizer']['patterns']['post'];
            $newSetup = clone $setUp;
            $newSetup->setDimension(new Dimension($form->canvasData->width, $form->canvasData->height));
            $newSetup->setCoords(new Coords($form->canvasData->left, $form->canvasData->top));

            return $this->imageManager->postImageResize(
                new Image($form->fileName, $form->image->tempName, $form->image->size, ''),
                $newSetup,
                Yii::$app->params['resizer']['paths']
            );
        }

        return '';
    }
}
