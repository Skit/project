<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 19.11.2018
 * Time: 9:09
 */

namespace backend\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;

class ContentKitBehavior extends Behavior
{
    public $contentAttribute = 'content';

    /**
     * @return array
     */
    public function events(): array
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'kits',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'kits',
        ];
    }

    /**
     * @throws \yii\base\ErrorException
     */
    public function kits($owner): void
    {
        $model = $owner->sender;
        $model->{$this->contentAttribute} = $this->keyboardStyle($model->{$this->contentAttribute});
    }

    private function keyboardStyle(string $content){

        return preg_replace('~\[(.+)\]~', '<span class="key">$1</span>', $content);
    }
}