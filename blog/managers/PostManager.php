<?php


namespace blog\managers;

use backend\forms\PostsForm;
use backend\models\Posts;
use blog\transfers\CategoryTransfer;
use blog\transfers\PostTransfer;

class PostManager
{
    private
        $postTransfer;

    protected
        $tags,
        $posts,
        $categories;

    public function __construct(PostTransfer $postTransfer)
    {
        $this->postTransfer = $postTransfer;
    }

    public function create(PostsForm $forms)
    {
        $post = Posts::create($forms);
        $this->postTransfer->save($post);
    }

    public function edit(PostsForm $forms)
    {
        $post = $this->postTransfer->byId($forms->id);
        $post = Posts::edit($forms, $post);
        $this->postTransfer->save($post);
    }

    public function activate()
    {

    }

    public function draft()
    {

    }
}