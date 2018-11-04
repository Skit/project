<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 03.11.2018
 * Time: 18:39
 */

namespace common\validators;

use yii\validators\RegularExpressionValidator;

class SlugValidator extends RegularExpressionValidator
{
    public $pattern = '~^[a-z0-9_-]*$~s';
    public $message = 'Only [a-z0-9_-] symbols are allowed.';
}