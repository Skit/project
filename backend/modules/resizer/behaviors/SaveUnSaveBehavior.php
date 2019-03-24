<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 19.11.2018
 * Time: 9:09
 */

namespace backend\modules\resizer\behaviors;

use common\helpers\FileHelper;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\web\Session;

class SaveUnSaveBehavior extends Behavior
{
    public $sessionKey = 'cropper';
    /**
     * @var Session
     */
    private $session;

    public function __construct(Session $session, $config = [])
    {
        parent::__construct($config);
        $this->session = $session;
    }

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
        if ($cropper = $this->session->get($this->sessionKey)) {
            FileHelper::copyDirectory($cropper['unsave'], $cropper['target'], ['recursive' => true]);
            FileHelper::removeDirectory($cropper['unsave']);
        }
    }
}