<?php


namespace blog\transfers;


use backend\models\Posts;
use common\exception\NotFoundException;

class PostTransfer
{
    /**
     * @param $id
     * @return Posts
     */
    public function byId($id): Posts
    {
        if (! $post = Posts::findOne($id)) {
            throw new NotFoundException('Post is not found.');
        }
        return $post;
    }

    /**
     * @param Posts $post
     */
    public function save(Posts $post): void
    {
        if (! $post->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    /**
     * @param Posts $post
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function update(Posts $post): void
    {
        if (! $post->update()) {
            throw new \RuntimeException('Updating error.');
        }
    }

    /**
     * @param Posts $post
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function remove(Posts $post): void
    {
        if (! $post->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }
}