<?php

namespace backend\models;

use common\components\behaviors\TimestampBehavior;
use Yii;
use yii\db\Expression;

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
class Categories extends \yii\db\ActiveRecord
{
    const
        ACTIVE = 1,
        INACTIVE = 0;

    public function beforeSave($insert)
    {
        $this->created_at = date('YmdHis');
        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%categories}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Posts::class, ['category_id' => 'id']);
    }
}
