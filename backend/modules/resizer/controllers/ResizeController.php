<?php

namespace backend\modules\resizer\controllers;

use yii\web\Controller;
use yii\web\HttpException;
use yii\web\UploadedFile;

/**
 * Default controller for the `translator` module
 */
class ResizeController extends Controller
{
    public function actionIndex(){

        var_dump($this->module->params); exit;
        print 'rrr';
    }
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionCropper()
    {
        $request = \Yii::$app->request;

        if (!$request->isAjax || !($imageData = $request->post('imageData'))
            || !($canvasData = $request->post('canvasData'))
            || !($image = UploadedFile::getInstanceByName( 'image')))
        {
            throw new HttpException(400, 'Empty data.');
        }

        $model = new \yii\base\DynamicModel(compact('image'));
        $model->addRule(['image'], 'image', $this->module->params['imageRule']);

        if (!$model->validate()) {
            throw new HttpException(400, $model->getFirstError('image'));
        }

        var_dump(UploadedFile::getInstance($model, 'image')); exit;

        //return (new InputTranslate($str))->getEn();
    }
}
