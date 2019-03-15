<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 27.11.2018
 * Time: 8:57
 */

namespace backend\components\behaviors;

use blog\managers\TagsManager;
use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class TagsBehavior extends Behavior
{
    public
        $idAttribute = 'id',
        $tagsAttribute = 'tags',
        $oldTagsSessionKey = 'oldTags';

    protected
        $session,
        $manager;

    public function __construct(TagsManager $manager, array $config = [])
    {
        $this->manager = $manager;
        $this->session = Yii::$app->session;
        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function events(): array
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'saveTags',
            ActiveRecord::EVENT_AFTER_UPDATE => 'saveTags',
            // TODO сделать отдельный матод для работы с тегами при удалении статьи!
            ActiveRecord::EVENT_AFTER_DELETE => 'saveTags',
            ActiveRecord::EVENT_BEFORE_INSERT => 'setOldTags',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'setOldTags',
        ];
    }

    public function setOldTags(): void
    {
        if (! isset($this->owner->oldAttributes[$this->tagsAttribute])) {
            $this->owner->oldAttributes = [$this->tagsAttribute => ''];
        }

        $this->session->set($this->oldTagsSessionKey, $this->owner->oldAttributes[$this->tagsAttribute]);
    }

    public function saveTags(): void
    {
        $oldTags = $this->session->get($this->oldTagsSessionKey);
        $owner = $this->owner;
        $this->manager->createFromString($owner->{$this->idAttribute}, $owner->{$this->tagsAttribute}, $oldTags);
    }
}