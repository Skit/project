<?php


namespace blog\managers;


use blog\services\TagService;
use blog\transfers\PostsTagsTransfer;
use blog\transfers\TagTransfer;

class TagsManager
{
    public
        $tagService,
        $tagTransfer,
        $postsTagsTransfer;

    public function __construct(TagTransfer $tagTransfer, TagService $tagService, PostsTagsTransfer $postsTagsTransfer)
    {
        $this->tagService = $tagService;
        $this->tagTransfer = $tagTransfer;
        $this->postsTagsTransfer = $postsTagsTransfer;
    }

    public function createFromString(int $id, string $tags, string $oldTags): void
    {
        // FIXME связи создает не корректно, frequency не уменьшает. Отсальное не проверено
        $old = $this->tagService->arrayFromString($oldTags);
        $new = $this->tagService->arrayFromString($tags);

        $deleteTags = $this->tagService->getDelete($new, $old);
        // TODO затестить если aggTags пустой массив
        $addTags = $this->tagService->getAdded($new, $old);

        $existAdded = $this->tagTransfer->getExistTags($addTags);
        $deleteTags = $this->tagTransfer->getExistTags($deleteTags);

        $this->frequencyDown($deleteTags);
        $this->frequencyUp($existAdded);

        $create = $this->tagService->getToCreate($addTags, $existAdded);
        $this->batchCreate($create);

        $this->batchLink($id, $addTags);
        $this->batchUnLink($id, $deleteTags);
    }

    /**
     * @param int $id
     * @param array $addTags
     * @return int
     * @throws \yii\db\Exception
     */
    public function batchLink(int $id, array $addTags): int
    {
        $addTags = $this->tagTransfer->getExistTags($addTags);
        $addTags = $this->tagService->tagsReferenceArray($id, $addTags);

        return $this->postsTagsTransfer->batchCreate($addTags);
    }

    /**
     * @param int $id
     * @param array $tags
     * @return int
     * @throws \yii\db\Exception
     */
    public function batchUnlink(int $id, array $tags): int
    {
        return $this->postsTagsTransfer->batchDelete(
            $this->tagService->tagsReferenceArray($id, $tags)
        );
    }

    /**
     * @param array $create
     * @return int
     * @throws \yii\db\Exception
     */
    protected function batchCreate(array $create): int
    {
        $tagsWithSlug = $this->tagService->addedSlugsArray($create);
        return $this->tagTransfer->batchCreate($tagsWithSlug);
    }

    /**
     * @param array $tags
     * @return array
     */
    protected function frequencyUp(array $tags): array
    {
        return $this->frequency($tags, 1);
    }

    /**
     * @param array $tags
     * @return array
     */
    protected function frequencyDown(array $tags): array
    {
        return $this->frequency($tags, -1);
    }


    /**
     * @param array $tags
     * @param int $value
     * @return array
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    private function frequency(array $tags, int $value): array
    {
        foreach ($tags as $key => $tag) {
            $this->tagTransfer->updateFrequency($tag, $value);

            if ($tag->frequency < 1) {
                $this->tagTransfer->delete($tag);
                unset($tags[$key]);
            }
        }

        return $tags;
    }
}