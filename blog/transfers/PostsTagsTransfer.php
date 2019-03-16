<?php


namespace blog\transfers;


use backend\models\PostsTags;
use Yii;

class PostsTagsTransfer
{
    /**
     * @param array $rows
     * @return int
     * @throws \yii\db\Exception
     */
    public function batchCreate(array $rows): int
    {
        return Yii::$app->db->createCommand()
            ->batchInsert(PostsTags::tableName(), ['post_id', 'tag_id'], $rows)
            ->execute();
    }

    /**
     * @param array $rows
     * @return int
     * @throws \yii\db\Exception
     */
    public function batchDelete(array $rows): int
    {
        $c = 0;
        foreach ($rows as $row) {
            $c += Yii::$app->db->createCommand()
                ->delete(PostsTags::tableName(),['post_id' => $row[0], 'tag_id' => $row[1]])
                ->execute();
        }

        return $c;
    }
}