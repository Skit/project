<?php


namespace backend\modules\resizer\forms;


use backend\modules\resizer\entities\Canvas;
use yii\base\Model;
use yii\helpers\Json;
use yii\web\UploadedFile;

/**
 * Class ImageForm
 * @package backend\modules\resizer\forms
 *
 * @property Canvas $canvasData
 */
class ImageForm extends Model
{
    public
        $image,
        $fileName,
        $canvasData;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fileName', 'canvasData'], 'string'],
            [['image'], 'image'],
        ];
    }

    public function beforeValidate(): bool
    {
        $this->image = UploadedFile::getInstanceByName('image');
        return parent::beforeValidate();
    }

    public function afterValidate()
    {
        $canvasData = Json::decode($this->canvasData, false);
        $this->canvasData = new Canvas($canvasData->left, $canvasData->top, $canvasData->width, $canvasData->height,
            $canvasData->naturalWidth, $canvasData->naturalHeight);

        return parent::afterValidate();
    }
}