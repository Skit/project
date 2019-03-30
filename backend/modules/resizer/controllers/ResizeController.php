<?php

namespace backend\modules\resizer\controllers;

use backend\forms\PostsForm;
use backend\modules\resizer\forms\ImageForm;
use backend\modules\resizer\services\Resizer;
use blog\fileManager\entities\Coords;
use blog\fileManager\entities\Dimension;
use blog\fileManager\ImageManager;
use blog\fileManager\source\Image;
use Yii;
use yii\base\DynamicModel;
use yii\base\Module;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Request;
use yii\web\UploadedFile;

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
            $setUp = Yii::$app->params['resizer']['patterns']['post'];
            $setUp->setDimension(new Dimension($form->canvasData->width, $form->canvasData->height));
            $setUp->setCoords(new Coords($form->canvasData->left, $form->canvasData->top));

            return $this->imageManager->postImageResize(
                new Image($form->fileName, $form->image->tempName, $form->image->size, ''),
                $setUp,
                Yii::$app->params['resizer']['paths']
            );
        }

     /*   if ($request->isAjax || !($this->canvasData = json_decode($request->post('canvasData')))
            || !($this->file = UploadedFile::getInstanceByName( 'image'))
            || !($formFileName = $request->post('fileName')))
        {
            return false;
        }

        if (!$this->service->init()) {
            // TODO можно возвращать ошибки или сделать метод как в validate()
            throw new HttpException(400, 'Data invalid.');
        }*/

        //return $this->service->cropper();
    }
}
