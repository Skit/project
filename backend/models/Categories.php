<?php

namespace backend\models;

use backend\behaviors\MetaTagsBehavior;
use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%categories}}".
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property string $meta_desc
 * @property string $meta_key
 * @property int $creator_id
 * @property string $created_at
 * @property string $updated_at
 * @property int $is_active
 *
 * @property Posts[] $posts
 */
class Categories extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%categories}}';
    }

    public static function create(Model $form)
    {
        $category = new static();
        $category->setAttributes($form->getAttributes(), false);
        $category->creator_id = Yii::$app->user->identity->id;

        return $category;
    }

    /**
     * @param Model $form
     * @param self $post
     */
    public static function edit(Model $form, self $categoty): void
    {
        $categoty->setAttributes($form->getAttributes(), false);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
            MetaTagsBehavior::class,
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Posts::class, ['category_id' => 'id']);
    }


}
