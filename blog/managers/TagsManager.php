<?php


namespace blog\managers;


use backend\models\Posts;
use backend\models\Tags;
use blog\services\TagService;
use blog\entities\TagsData;
use blog\transfers\TagTransfer;

/**
 * Class TagsManager
 * @package blog\managers
 */
class TagsManager
{
    private
        $newTags = [],
        $oldTags = [];

    public
        $service,
        $transfer;

    /**
     * TagsManager constructor.
     * @param TagTransfer $tagTransfer
     * @param TagService $tagService
     */
    public function __construct(TagTransfer $tagTransfer, TagService $tagService)
    {
        $this->service = $tagService;
        $this->transfer = $tagTransfer;
    }

    /**
     * @param Posts $posts
     * @return TagsManager
     */
    public function fromModel(Posts $posts): self
    {
        $newTagsStr = $posts->tags;
        $oldTagsStr = $posts->oldAttributes['tags'] ?? '';

        $this->fromNewOldString($newTagsStr, $oldTagsStr);

        return $this;
    }

    /**
     * @param string $newTags
     * @param string $oldTags
     * @return TagsManager
     */
    public function fromNewOldString(string $newTags, string $oldTags): self
    {
        if ($newTags !== $oldTags) {
            $this->oldTags = $this->service->arrayFromString($oldTags);
            $this->newTags = $this->service->arrayFromString($newTags);
        }

        return $this;
    }

    /**
     * @return TagsData
     */
    public function get(): TagsData
    {
        $addedTags = $this->service->getToAdd($this->newTags, $this->oldTags);
        $toDelete = $this->service->getToDelete($this->newTags, $this->oldTags);
        $existTag = $this->transfer->getExistTags($addedTags);
        $notExistTag = $this->service->getNewTags($addedTags, $existTag);

        return new TagsData(
            $existTag,
            $this->savedFromArray($notExistTag),
            $this->transfer->getExistTags($toDelete)
        );
    }

    /**
     * @param Tags $tag
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function frequencyDown(Tags $tag)
    {
        if($tag->frequency === 1) {
            $this->transfer->delete($tag);
        } else {
            $tag->frequencyDown();
            $this->transfer->save($tag);
        }
    }

    /**
     * @param Tags $tag
     */
    public function frequencyUp(Tags $tag)
    {
        $tag->frequencyUp();
        $this->transfer->save($tag);
    }

    /**
     * @param array $tags
     */
    public function savedFromArray(array $tags)
    {
        $result = [];
        foreach ($tags as $name) {
            $tag = Tags::create($name, $this->service->nameToSlug($name));
            $this->transfer->save($tag);
            $result[] = $tag;
        }

        return $result;
    }
}
