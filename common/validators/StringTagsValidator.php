<?php


namespace common\validators;


use yii\validators\EachValidator;

class StringTagsValidator extends EachValidator
{
    public
        $max,
        $allowMessageFromRule = false;

    public function init()
    {
        $this->message = "Each tags should contain at most {$this->max} characters.";
        $this->rule = ['string', 'max' => $this->max];
    }

    public function validateAttribute($model, $attribute)
    {
        $model->$attribute = explode(',', $model->$attribute);
        $result =  parent::validateAttribute($model, $attribute);
        $model->$attribute = implode(',', $model->$attribute);

        return $result;
    }
}
