<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 03.11.2018
 * Time: 16:42
 */

namespace blog\transfers;

use backend\models\Tags;
use common\exception\NotFoundException;
use Yii;

class TagTransfer
{

    /**
     * Set rows as [[name=>tagName, slug=>tagSlug]]
     *
     * @param array $rows
     * @return int
     * @throws \yii\db\Exception
     */
    public function batchCreate(array $tagsWithSlug): int
    {
        return Yii::$app->db->createCommand()
            ->batchInsert(Tags::tableName(), ['name', 'slug'], $tagsWithSlug)
            ->execute();
    }
    /**
     * @param array $tags
     * @return array
     */
    public function getExistTags(array $tags): array
    {
        return Tags::find()
            ->where(['in', 'name', $tags])
            ->all();
    }

    /**
     * @param $id
     * @return Tags
     */
    public function getById($id): Tags
    {
        if (! $tag = Tags::findOne($id)) {
            throw new NotFoundException('Tag is not found.');
        }
        return $tag;
    }

    /**
     * @param $name
     * @return Tags
     */
    public function getByName($name): Tags
    {
        if (! $tag = Tags::findOne(['name' => $name])) {
            throw new NotFoundException('Tag is not found.');
        }
        return $tag;
    }

    /**
     * @param array $tagsName
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getAllByName(array $tagsName)
    {
        if(! $tags = $this->getExistTags($tagsName)) {
            throw new NotFoundException('Tags are not found.');
        }

        return $tags;
    }

    /**
     * @param Tags $tag
     */
    public function save(Tags $tag): void
    {
        if (! $tag->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    /**
     * @param Tags $tag
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function delete(Tags $tag): void
    {
        if (! $tag->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }

    public function updateFrequency(Tags $tag, int $value)
    {
        if (! $tag->updateCounters(['frequency' => $value])) {
            throw new \RuntimeException('Unknown error.');
        }
    }
}
