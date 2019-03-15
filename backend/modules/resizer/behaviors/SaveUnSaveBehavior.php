<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 19.11.2018
 * Time: 9:09
 */

namespace backend\modules\resizer\behaviors;

use Yii;
use common\helpers\FileHelper;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class SaveUnSaveBehavior extends Behavior
{
    /**
     * @return array
     */
    public function events(): array
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'deleteUnsafeAfterCopy',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'deleteUnsafeAfterCopy',
        ];
    }

    /**
     * @throws \yii\base\ErrorException
     */
    public function deleteUnsafeAfterCopy(): void
    {
        if ($cropper = Yii::$app->session->get('cropper')) {
            FileHelper::copyDirectory($cropper['unsave'], $cropper['target'], ['recursive' => true]);
            FileHelper::removeDirectory($cropper['unsave']);
        }
    }
}