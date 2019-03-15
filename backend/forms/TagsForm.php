<?php


namespace backend\forms;


use blog\managers\FormsManager;
use common\validators\StringTagsValidator;

class TagsForm extends FormsManager
{
    public $tags;

    public function rules(): array
    {
        return [
            [['tags'], 'filter', 'filter' => 'strtolower'],
            ['tags', StringTagsValidator::class,  'max' => 50],
        ];
    }

    public function replaceAttributes(): array
    {

        return [
            PostsForm::class => ['tags' => $this->tags],
            FormsManager::class => ['tags' => $this->tags]
        ];
    }
}
