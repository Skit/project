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
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%posts_tags}}';
    }

    public static function batchCreate(array $rows): int
    {
        return Yii::$app->db->createCommand()
            ->batchInsert(self::tableName(), ['post_id', 'tag_id'], $rows)
            ->execute();
    }

    public static function batchDelete(array $rows): int
    {
        $c = 0;
        foreach ($rows as $row) {
            $c += Yii::$app->db->createCommand()
                ->delete(self::tableName(),['post_id' => $row[0], 'tag_id' => $row[1]])->execute();
        }

        return $c;
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
