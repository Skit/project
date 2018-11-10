<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 10.11.2018
 * Time: 9:45
 */

namespace common\services\forms;

use backend\models\Posts;

/**
 * Class Service
 * @package common\services\forms
 */
class PostsService extends Service
{
    public function __construct(Posts $model)
    {
        parent::__construct($model);
    }
}