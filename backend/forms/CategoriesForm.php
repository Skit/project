<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 03.11.2018
 * Time: 18:37
 */

namespace backend\forms;

use yii\base\Model;

class CategoriesForm extends Model
{
    public
        $title,
        $slug,
        $description,
        $meta_desc,
        $meta_key,
        $creator_id,
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
            [['title', 'slug', 'meta_desc', 'meta_key'], 'string', 'max' => 255],
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
            'meta_desc' => 'Meta Desc',
            'meta_key' => 'Meta Key',
            'creator_id' => 'Creator ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_active' => 'Is Active',
        ];
    }
}