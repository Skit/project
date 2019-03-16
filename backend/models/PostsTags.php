<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%posts_tags}}".
 *
 * @property int $id
 * @property int $tag_id
 * @property int $post_id
 *
 * @property Tags $tag
 * @property Posts $post
 */
class PostsTags extends ActiveRecord
{
    /**
     * @param int $postId
     * @param int $tagId
     * @return PostsTags
     */
    public static function create(int $postId, int $tagId): self
    {
        $postTag = new static();
        $postTag->post_id = $postId;
        $postTag->tag_id = $tagId;

        return $postTag;
    }

    public function delete()
    {

    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%posts_tags}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTag()
    {
        return $this->hasOne(Tags::class, ['id' => 'tag_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Posts::class, ['id' => 'post_id']);
    }
}
