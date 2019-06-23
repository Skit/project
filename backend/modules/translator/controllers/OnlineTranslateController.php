<?php

namespace backend\modules\translator\controllers;

use yii;
use backend\modules\translator\models\InputTranslate;
use yii\web\Controller;
use yii\web\HttpException;

/**
 * Default controller for the `translator` module
 */
class OnlineTranslateController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $request = \Yii::$app->request;

        if(!$request->isAjax || !($str = $request->post('str'))){
            throw new HttpException(500);
        }

        return (new InputTranslate($str))->getTranslate();
    }

    /**
     * @return string|string[]|null
     * @throws HttpException
     */
    public function actionGetSlug()
    {
        $request = Yii::$app->request;

        if(!$request->isAjax || !($str = $request->post('str'))){
            throw new HttpException(500);
        }

        return (new InputTranslate($str))->getSlug();
    }
}
