<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 10.11.2018
 * Time: 11:36
 */

namespace common\models;

use yii\base\Model;

class Forms extends Model
{
    public function isActive(){

        return [
            Constants::ACTIVE => 'Yes',
            Constants::INACTIVE =>'No',
        ];
    }
}