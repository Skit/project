<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 09.11.2018
 * Time: 22:13
 */

namespace common\components\behaviors;

use yii\db\BaseActiveRecord;

class TimestampBehavior extends \yii\behaviors\TimestampBehavior
{
    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => $this->createdAtAttribute,
                BaseActiveRecord::EVENT_BEFORE_UPDATE => $this->updatedAtAttribute,
            ];
        }
    }

}