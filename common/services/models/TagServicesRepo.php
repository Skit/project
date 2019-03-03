<?php


namespace common\services\models;

use backend\models\PostsTags;
use backend\models\Tags;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

class TagServicesRepo
{
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
     * @param $name
     * @param $slug
     * @return Tags
     */
    public function create($name, $slug): Tags
    {
        $tag = Tags::create($name, $slug);

        if (! $tag->save()) {
            throw new \RuntimeException('Saving error.');
        }

        return $tag;
    }

    /**
     * @return array|null
     */
    public function getAll(): ?Array
    {
        return Tags::find()->all();
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
     * @param array $tagsName
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getAllByNames(array $tagsName)
    {
        return Tags::find()
            ->where(['in', 'name', $tagsName])
            ->all();
    }

    /**
     * @param int $id
     * @param array $tags
     * @return array
     */
    public function setTagsReference(int $id, array $tags): array
    {
        $tagReference = [];
        foreach ($tags as $tag) {
            $tagReference[] = [$id, $tag->id];
        }

        return $tagReference;
    }

    /**
     * @param string $tagsString
     * @return array
     */
    public function arrayFromString(string $tagsString): array
    {
        return $this->_normalizeTags(explode(',', $tagsString));
    }

    /**
     * @param array $tags
     * @return array
     */
    public function setTagsSlug(array $tags): array
    {
        $tagNSlug = [];
        foreach ($tags as $tag) {
            $tagNSlug[] = [
                'name' => $tag,
                'slug' => Inflector::slug($tag)
            ];
        }

        return $tagNSlug;
    }

    /**
     * @param array $new
     * @param array $old
     * @return array
     */
    public function getDelete(array $new, array $old): array
    {
        return $this->_compareTagsArray([$old, $new]);
    }

    /**
     * @param array $new
     * @param array $old
     * @return array
     */
    public function getAdded(array $new, array $old): array
    {
        return $this->_compareTagsArray([$new, $old]);
    }

    /**
     * @param array $added
     * @param array $exist
     * @return array
     */
    public function getToCreate(array $added, array $exist): array
    {
        return $this->_compareTagsArray([$added, ArrayHelper::getColumn($exist, 'name')]);
    }

    /**
     * @param array $tags
     * @param int $value
     * @return array
     */
    protected function frequency(array $tags, int $value): array
    {
        foreach ($tags as $key => $tag) {

            if (! $tag->updateFrequency($value)) {
                throw new \RuntimeException('Unknown error.');
            }

            if ($tag->frequency < 1) {

                if (! $tag->delete()) {
                    throw new \RuntimeException('Unknown error.');
                }
                unset($tags[$key]);
            }
        }

        return $tags;
    }

    /**
     * " Tag   2019 " to "Tag 2019"
     * @param array $tags
     * @return array
     */
    private function _normalizeTags(array $tags)
    {
        return array_map(function ($v){
            return preg_replace('~\s+~', ' ', trim($v));
        }, $tags);
    }

    /**
     * @param array $data
     * @return array
     */
    private function _compareTagsArray(array $data): array
    {
        return array_diff($data[0], $data[1]);
    }

}