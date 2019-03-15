<?php


namespace backend\forms;


use backend\models\Categories;
use blog\managers\FormsManager;

class CategoryForm extends FormsManager
{
    public $category_id;

    public function rules(): array
    {
        return [
            [['category_id'], 'required'],
            [['category_id'], 'integer'],
            ['category_id', 'exist', 'targetAttribute' => 'id', 'targetClass' => Categories::class],
        ];
    }

    public function replaceAttributes(): array
    {
        return [PostsForm::class => ['category_id' => $this->category_id]];
    }
}