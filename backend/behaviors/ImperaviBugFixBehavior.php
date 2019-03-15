<?php

namespace backend\behaviors;

use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

class ImperaviBugFixBehavior extends AttributeBehavior
{
    public $attribute = 'content';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        $this->attributes = [
            BaseActiveRecord::EVENT_BEFORE_INSERT => $this->attribute,
            BaseActiveRecord::EVENT_BEFORE_UPDATE => $this->attribute,
        ];
    }

    public function getValue($event)
    {
       return str_replace(' "=""', '', $this->owner->{$this->attribute});
    }
}