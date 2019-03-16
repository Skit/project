<?php


namespace backend\behaviors;


use blog\entities\MetaTags;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\helpers\Json;

class MetaTagsBehavior extends Behavior
{
    public
        $mainAttribute,
        $modelAttribute = 'meta_tags';

    public function init()
    {
        parent::init();
        $this->mainAttribute = $this->mainAttribute ?: $this->modelAttribute;
    }

    public function events(): array
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'onAfterFind',
            ActiveRecord::EVENT_BEFORE_INSERT => 'onBeforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'onBeforeSave',
        ];
    }

    public function onAfterFind(Event $event): void
    {
        $model = $event->sender;
        $metaTags = Json::decode($model->getAttribute($this->mainAttribute));

        if(empty($metaTags)) {
            $model->{$this->mainAttribute} = new MetaTags('', '', '');
        } else {
            $model->{$this->mainAttribute} = new MetaTags($metaTags['title'], $metaTags['description'], $metaTags['keywords']);
        }
    }

    public function onBeforeSave(Event $event): void
    {
        $json = Json::encode([
            'title' => $event->sender->{$this->mainAttribute}->title,
            'description' => $event->sender->{$this->mainAttribute}->description,
            'keywords' => $event->sender->{$this->mainAttribute}->keywords,
        ]);
        $event->sender->setAttribute($this->mainAttribute, $json);
    }
}
