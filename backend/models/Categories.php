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
    public const IS_ACTIVE = 1;

    /**
     * @param string $title
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public static function deleteByTitle(string $title): bool
    {
        $category = self::find()
            ->where(['title' => $title])
            ->limit(1)
            ->one();

        return $category->delete() > 0 ? true : false;
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
