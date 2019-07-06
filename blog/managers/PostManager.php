<?php


namespace blog\managers;

use backend\forms\PostsForm;
use backend\models\Posts;
use blog\transfers\PostTransfer;
use yii\base\Model;

class PostManager
{
    private
        $postTransfer;

    protected
        $tags,
        $posts,
        $categories;

    /**
     * @var TagsManager
     */
    private $tagsManager;


    public function __construct(PostTransfer $postTransfer, TagsManager $tagsManager)
    {
        $this->postTransfer = $postTransfer;
        $this->tagsManager = $tagsManager;
    }

    /**
     * @param PostsForm $forms
     * @return Posts|Model
     */
    public function create(PostsForm $forms): Posts
    {
        $post = Posts::create($forms);
        $tagsData = $this->tagsManager->fromModel($post)->load();

        $this->linkTags($post, $tagsData->savedTag, $tagsData->existTag);
        $this->unlinkTags($post, $tagsData->toDelete);

        $this->postTransfer->save($post);

        return $post;
    }

    /**
     * @param PostsForm|Model $forms
     */
    public function edit(PostsForm $forms)
    {
        $post = $this->postTransfer->byId($forms->id);
        Posts::edit($forms, $post);
        $tagsData = $this->tagsManager->fromModel($post)->load();

        $this->linkTags($post, $tagsData->savedTag, $tagsData->existTag);
        $this->unlinkTags($post, $tagsData->toDelete);

        $this->postTransfer->save($post);
    }

    /**
     * @param Posts $post
     * @param array ...$tagsCollection
     */
    protected function linkTags(Posts $post,  array ...$tagsCollection)
    {
        foreach ($tagsCollection as $tags) {
            foreach ($tags as $tag) {

                if(! $tag->isNewRecord) {
                    $this->tagsManager->frequencyUp($tag);
                }
                $post->addLinkTag($tag);
            }
        }
    }

    /**
     * @param Posts $post
     * @param array $tags
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    protected function unlinkTags(Posts $post, array $tags)
    {
        foreach ($tags as $tag) {
            $this->tagsManager->frequencyDown($tag);
            $post->addUnlinkTag($tag);
        }
    }
}