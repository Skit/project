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
        if(trim($tagsString) === '') {
            $result = [];
        } else {
            $result = $this->_normalizeTags(explode($delimiter, $tagsString));
        }

        return $result;
    }

    /**
     * @param array $new
     * @param array $old
     * @return array
     */
    public function getToAdd(array $new, array $old): array
    {
        return $this->_compareTagsArray([$new, $old]);
    }

    /**
     * @param array $new
     * @param array $old
     * @return array
     */
    public function getToDelete(array $new, array $old): array
    {
        return $this->_compareTagsArray([$old, $new]);
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
                'slug' => $this->nameToSlug($tag)
            ];
        }

        return $tagNSlug;
    }

    /**
     * @param string $tagName
     * @return string
     */
    public function nameToSlug(string $tagName)
    {
        return Inflector::slug($tagName);
    }

    /**
     * @param array $added
     * @param array $exist
     * @return array
     */
    public function getNewTags(array $added, array $exist): array
    {
        return $this->_compareTagsArray([$added, array_column($exist, 'name')]);
    }

    /**
     * @param array $tags
     * @return array
     */
    private function _normalizeTags(array $tags)
    {
        return array_map(function ($v){
            return $this->_normalizeString($v);
        }, $tags);
    }

    /**
     * " Tag   2019 " to "Tag 2019"
     *
     * @param string $str
     * @return string|string[]|null
     */
    private function _normalizeString(string $str)
    {
        $str = mb_strtolower(trim($str));
        return preg_replace('~\s+~', ' ', $str);
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