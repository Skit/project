<?php


namespace blog\services;


use yii\helpers\Inflector;

class TagService
{
    /**
     * @param string $tagsString
     * @return array
     */
    public function arrayFromString(string $tagsString, string $delimiter = ','): array
    {
        // TODO проверить, если пустая строка
        return $this->_normalizeTags(explode($delimiter, $tagsString));
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
     * @param array $tags
     * @return array
     */
    public function addedSlugsArray(array $tags): array
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
     * @param int $id
     * @param array $tags
     * @return array
     */
    public function tagsReferenceArray(int $id, array $tags): array
    {
        $tagReference = [];
        foreach ($tags as $tag) {
            $tagReference[] = [$id, $tag->id];
        }

        return $tagReference;
    }

    /**
     * @param array $added
     * @param array $exist
     * @return array
     */
    public function getToCreate(array $added, array $exist): array
    {
        // TODO затестить array_column
        return $this->_compareTagsArray([$added, array_column($exist, 'name')]);
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