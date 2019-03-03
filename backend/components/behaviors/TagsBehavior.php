<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 27.11.2018
 * Time: 8:57
 */

namespace backend\components\behaviors;

use Yii;
use common\services\models\TagsService;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class TagsBehavior extends Behavior
{
    public $attribute = 'tags';

    protected
        $session,
        $service;

    public function __construct(TagsService $service, array $config = [])
    {
        $this->service = $service;
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
            ActiveRecord::EVENT_AFTER_DELETE => 'saveTags',
            ActiveRecord::EVENT_BEFORE_INSERT => 'setOldTags',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'setOldTags',
            ActiveRecord::EVENT_BEFORE_DELETE => 'setOldTags',
        ];
    }

    public function setOldTags(): void
    {
        if (! isset($this->owner->oldAttributes[$this->attribute])) {
            $this->owner->oldAttributes = [$this->attribute => ''];
        }

        $this->session->set('Tags', ['old' => $this->owner->oldAttributes[$this->attribute]]);
    }

    public function saveTags(): void
    {
        $oldTags = $this->session->get('Tags')['old'];
        $this->service->operation($this->owner, $oldTags);
    }
}