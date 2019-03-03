<?php

namespace backend\modules\resizer\controllers;

use backend\modules\resizer\services\Resizer;
use yii\base\Module;
use yii\web\Controller;
use yii\web\HttpException;

/**
 * Default controller for the `translator` module
 */
class ResizeController extends Controller
{
    public $service;

    public function __construct(string $id, Module $module, Resizer $service, array $config = [])
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionCropper()
    {
        if (!$this->service->init()) {
            // TODO можно возвращать ошибки или сделать метод как в validate()
            throw new HttpException(400, 'Data invalid.');
        }

        return $this->service->cropper();
    }
}
