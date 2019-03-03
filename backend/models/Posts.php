<?php

namespace backend\models;

use backend\components\behaviors\TagsBehavior;
use backend\modules\resizer\behaviors\SaveUnSaveBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%posts}}".
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $image_url
 * @property string $video_url
 * @property string $content
 * @property string $preview
 * @property string $tags
 * @property string $meta_desc
 * @property string $meta_key
 * @property int $creator_id
 * @property int $category_id
 * @property string $created_at
 * @property string $updated_at
 * @property int $count_view
 * @property int $is_highlight
 * @property int $is_active
 *
 * @property Categories $category
 * @property PostsTags[] $postsTags
 */
class Posts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%posts}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            SaveUnSaveBehavior::class,
            TagsBehavior::class,
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        //$this->creator_id = \Yii::$app->user->id;

       // $this->created_at = new Expression('NOW()');
        $this->creator_id = Yii::$app->user->identity->id;
        return parent::beforeSave($insert);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::class, ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostsTags()
    {
        return $this->hasMany(PostsTags::class, ['post_id' => 'id']);
    }
}
