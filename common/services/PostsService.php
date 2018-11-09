<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 08.11.2018
 * Time: 21:51
 */

namespace common\services;

use backend\forms\PostsForm;
use backend\models\Posts;

class PostsService extends Service
{
    protected $model;

    public function __construct(Posts $posts)
    {
        $this->model = $posts;
    }

/*    public function create(PostsForm $post, int $validate=1): Posts
    {

        if($validate){
            $post->validate();
        }

        $this->model->setAttributes($post->getAttributes(), false);
        $this->model->save();

        return $this->model;
    }*/

}