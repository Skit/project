<?php


namespace backend\forms;

use blog\entities\MetaTags;
use blog\managers\FormsManager;

class MetaTagsForm extends FormsManager
{
    public
        $title,
        $description,
        $keywords;

    public function rules(): array
    {
        return [
            [['title', 'description', 'keywords'], 'string', 'max' => 255],
        ];
    }

    public function replaceAttributes(): array
    {
        return [
            PostsForm::class => ['meta_tags' => $this],
            CategoriesForm::class => ['meta_tags' => $this],
        ];
    }

    public function setMeta_tags(MetaTags $data)
    {
        $this->title = $data->title;
        $this->description = $data->description;
        $this->keywords = $data->keywords;
    }
}
