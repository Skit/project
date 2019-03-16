<?php


namespace backend\behaviors;


use blog\transfers\PostsTagsTransfer;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;

class PostTagsBehavior extends Behavior
{

    /**
     * @var PostsTagsTransfer
     */
    private $transfer;

    /**
     * PostTagsBehavior constructor.
     * @param PostsTagsTransfer $postsTagsTransfer
     * @param array $config
     */
    public function __construct(PostsTagsTransfer $postsTagsTransfer, $config = [])
    {
        parent::__construct($config);
        $this->transfer = $postsTagsTransfer;
    }

    public function events(): array
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'link',
            ActiveRecord::EVENT_AFTER_UPDATE => 'link',
        ];
    }

    /**
     * @param Event $event
     */
    public function link(Event $event): void
    {
        $model = $event->sender;

        if ($link = $this->_getLinksArray($model->id, $model->linkTags)){
            $this->transfer->batchCreate($link);
        }

        if($unlink = $this->_getLinksArray($model->id, $model->unlinkTags)) {
            $this->transfer->batchDelete($unlink);
        }
    }

    /**
     * @param int $posId
     * @param array $tagIds
     * @return array
     */
    private function _getLinksArray(int $posId, array $tags)
    {
        foreach ($tags as $tag) {
            $result[] = [$posId, $tag->id];
        }
        return $result ?? [];
    }
}