<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 08.11.2018
 * Time: 21:51
 */

namespace common\services\models;

use backend\models\Categories;

class CategoriesService extends Service
{
    protected $model;

    public function __construct(Categories $posts)
    {
        $this->model = $posts;
    }

}