<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 10.11.2018
 * Time: 9:45
 */

namespace common\services\forms;


use backend\models\Categories;

/**
 * Class Service
 * @package common\services\forms
 */
class CategoriesService extends Service
{
    public function __construct(Categories $model)
    {
        parent::__construct($model);
    }
}