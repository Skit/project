<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 03.11.2018
 * Time: 16:42
 */

namespace blog\repositories;

use backend\models\Tags;

class TagRepository
{
    public function get($id): Tags
    {
        if (!$tag = Tags::findOne($id)) {
            throw new NotFoundException('Tag is not found.');
        }
        return $tag;
    }
    public function findByName($name): Tags
    {
        if (!$tag = Tags::findOne(['name' => $name])) {
            throw new NotFoundException('Tag is not found.');
        }
        return $tag;
    }
    public function save(Tags $tag): void
    {
        if (!$tag->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }
    public function remove(Tags $tag): void
    {
        if (!$tag->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }
}