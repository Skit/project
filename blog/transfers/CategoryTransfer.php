<?php


namespace blog\transfers;


use backend\models\Categories;
use common\exception\NotFoundException;
use RuntimeException;

class CategoryTransfer
{
    /**
     * @param $id
     * @return Categories
     */
    public function byId($id): Categories
    {
        if (! $post = Categories::findOne($id)) {
            throw new NotFoundException('Post is not found.');
        }
        return $post;
    }

    /**
     * @param Categories $post
     */
    public function save(Categories $category): void
    {
        if (! $category->save()) {
            throw new RuntimeException('Saving error.');
        }
    }

    /**
     * @param Categories $post
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function update(Categories $category): void
    {
        if (! $category->update()) {
            throw new RuntimeException('Updating error.');
        }
    }
}