<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 03.11.2018
 * Time: 16:53
 */

namespace common\services;

use backend\models\Tags;
use backend\forms\TagsForm;
use common\exception\NotFoundException;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\base\Model;

class TagsService
{
    /**
     * @param Model $model
     * @return int
     * @throws \yii\db\Exception
     */
    public function operation(Model $model): int
    {
        $old = self::arrayFromString($model->oldAttributes->tags);
        $new = self::arrayFromString($model->tags);

        $deleteTags = self::getDelete($new, $old);
        $addTags = self::getAdded($new, $old);

        $existAdded = self::getExistTags($addTags);
        $deleteTags = self::getExistTags($deleteTags);

        self::frequencyDown($deleteTags);
        self::frequencyUp($existAdded);

        $create = self::getToCreate($addTags, $existAdded);

        return self::batchCreate($create);
    }

    /**
     * @param TagsForm $form
     * @param bool $validate
     * @return Tags
     */
    public function createFromForm(TagsForm $form, bool $validate=true): Tags
    {
        if($validate && !$form->validate()) {
            throw new \RuntimeException('Data is not valid.');
        }

        return self::create($form->name, $form->slug);
    }

    /**
     * @param array $tags
     * @return int
     * @throws \yii\db\Exception
     */
    public function batchCreate(array $tags): int
    {
        return Tags::batchCreate(
            self::setTagsSlug($tags)
        );
    }

    /**
     * @param string $tagsString
     * @return array
     */
    public function arrayFromString(string $tagsString): array
    {
        return explode(',',
            preg_replace('~\s~', '', mb_strtolower($tagsString))
        );
    }

    /**
     * @param string $tagsString
     * @return array
     */
    public function setTagsSlug(array $tags): array
    {
        $tagNSlug = [];
        foreach ($tags as $tag)
        {
            $tagNSlug[] = [
                'name' => $tag,
                'slug' => Inflector::slug($tag)
            ];
        }

        return $tagNSlug;
    }

    /**
     * @param array $tags
     * @return array
     */
    public function getExistTags(array $tags): array
    {
        return Tags::find()
            ->where(['in','name', $tags])
            ->all();
    }

    /**
     * @param $name
     * @param $slug
     * @return Tags
     */
    public function create($name, $slug): Tags
    {
        $tag = Tags::create($name, $slug);

        if (!$tag->save()) {
            throw new \RuntimeException('Saving error.');
        }

        return $tag;
    }

    /**
     * @param $name
     * @return Tags|null
     */
    public function getByName($name): ?Tags
    {
        return Tags::findOne(['name' => $name]);
    }

    /**
     * @param array $tags
     * @return array
     */
    public function frequencyUp(array $tags): array
    {
        return self::_frequency($tags, 1);
    }

    /**
     * @param array $tags
     * @return array
     */
    public function frequencyDown(array $tags): array
    {
        return self::_frequency($tags, -1);
    }

    /**
     * @param array $new
     * @param array $old
     * @return array
     */
    public function getDelete(array $new, array $old): array
    {
        return self::_compareTagsArray([$old, $new]);
    }

    /**
     * @param array $new
     * @param array $old
     * @return array
     */
    public function getAdded(array $new, array $old): array
    {
        return self::_compareTagsArray([$new, $old]);
    }

    /**
     * @param array $added
     * @param array $exist
     * @return array
     */
    public function getToCreate(array $added, array $exist): array
    {
        return self::_compareTagsArray([$added, ArrayHelper::getColumn($exist, 'name')]);
    }

    /**
     * @param array $data
     * @return array
     */
    private function  _compareTagsArray(array $data): array
    {
        return array_diff($data[0], $data[1]);
    }

    /**
     * @param array $tags
     * @param int $value
     * @return array
     */
    private function _frequency(array $tags, int $value): array
    {
        foreach ($tags as $key => $tag){

            if(!$tag->updateFrequency($value)) {
                throw new \RuntimeException('Unknown error.');
            }

            if($tag->frequency < 1) {

                if(!$tag->delete()) {
                    throw new \RuntimeException('Unknown error.');
                }
                unset($tags[$key]);
            }
        }

        return $tags;
    }
}