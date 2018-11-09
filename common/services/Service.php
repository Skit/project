<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 09.11.2018
 * Time: 22:25
 */

namespace common\services;

use yii\base\Model;
use yii\db\ActiveRecord;

class Service
{
    protected $model;

    public function __construct(ActiveRecord $model)
    {
        $this->model = $model;
    }

    public function create(Model $post, int $validate=1): Model
    {
        if($validate){
            $post->validate();
        }

        $this->model->setAttributes($post->getAttributes(), false);
        $this->model->save();

        return $this->model;
    }
}