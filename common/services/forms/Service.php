<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 10.11.2018
 * Time: 9:45
 */

namespace common\services\forms;

use common\models\Constants;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class Service
 * @package common\services\forms
 */
class Service
{
    protected $model;

    /**
     * Service constructor.
     * @param ActiveRecord $model
     */
    public function __construct(ActiveRecord $model)
    {
        $this->model = $model;
    }

    /**
     * @param string $from
     * @param string $to
     * @return array
     */
    public function dropDownList(string $to='title', string $from='id'): array
    {
        return ArrayHelper::map(
            self::getAll(true), $from, $to
        );
    }

    public function getAll($asArray=false){

        $records = self::records();
        return $asArray ? $records->allAsArray() : $records->all();
    }

    public function getOne($asArray=false){

        $records = self::records();
        return $asArray ? $records->oneAsArray() : $records->one();
    }

    /**
     * @return array
     */
    protected function oneAsArray(): array
    {
        return $this->model->asArray()->one();
    }

    /**
     * @return array
     */
    protected function one(): array
    {
        return $this->model->one();
    }

    /**
     * @return array
     */
    protected function allAsArray(): array
    {
        return $this->model->asArray()->all();
    }

    /**
     * @return array
     */
    protected function all(): array
    {
        return $this->model->all();
    }

    /**
     * @return Service
     */
    protected function records(): self
    {
        $this->model = $this->model->find()
            ->where(['is_active'=>Constants::ACTIVE]);

        return $this;
    }
}