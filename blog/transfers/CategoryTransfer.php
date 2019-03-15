<?php


namespace blog\transfers;


use backend\models\Categories;
use common\exception\NotFoundException;

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
}