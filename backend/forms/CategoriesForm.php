<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 03.11.2018
 * Time: 18:37
 */

namespace backend\forms;

use blog\managers\FormsManager;

/**
 * Class CategoriesForm
 *
 * @property int $id
 *
 * @package backend\forms
 */
class CategoriesForm extends FormsManager
{
    public
        $id,
        $title,
        $slug,
        $description,
        $meta_tags,
        $creator_id,
        $created_at,
        $is_active;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'slug'], 'required'],
            [['description'], 'string'],
            [['is_active'], 'integer'],
            [['title', 'slug'], 'string', 'max' => 255],
            [['meta_tags'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'slug' => 'Slug',
            'description' => 'Description',
            'creator_id' => 'Creator ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_active' => 'Is Active',
        ];
    }
}