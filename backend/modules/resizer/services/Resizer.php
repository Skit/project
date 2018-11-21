<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 15.11.2018
 * Time: 8:16
 */

namespace backend\modules\resizer\services;

use backend\modules\resizer\models;
use common\helpers\FileHelper;
use Yii;
use yii\web\UploadedFile;
use RuntimeException;

class Resizer
{
    private
        /**
         * @var
         */
        $file,
        /**
         * Json canvas data object
         * @var object
         */
        $canvasData,
        /**
         * Object extension
         * @var object \Imagick
         */
        $imagick,
        /**
         * @var string request form name
         */
        $client,
        /**
         * @var array
         */
        $settings,
        /**
         * @var string
         */
        $resizeFileName,
        /**
         * @var string
         */
        $originalFileName,
        /**
         * @var string
         */
        $unsafeDir;


    public function init(): bool
    {
        $request = Yii::$app->request;
        if (!$request->isAjax || !($this->canvasData = json_decode($request->post('canvasData')))
            || !($this->file = UploadedFile::getInstanceByName( 'image'))
            || !($formFileName = $request->post('fileName'))
            || !($this->client = $request->post('client')))
        {
            return false;
        }

        $this->settings = Yii::$app->getModule('resizer')->clientSettings($this->client);
        $targetPath = FileHelper::replacer($this->settings['uploadPath']);
        $this->unsafeDir = FileHelper::replacer($targetPath . DS . $this->settings['relativeUnsafePath']);

        $this->resizeFileName = self::createFileName($formFileName, $this->settings['size']['width'],
            $this->settings['size']['height']);

        $this->originalFileName = self::createFileName($formFileName, $this->canvasData->naturalWidth,
            $this->canvasData->naturalHeight);

        Yii::$app->session->set('cropper', [
            'unsave' => $this->unsafeDir,
            'target' => $targetPath
        ]);

        return true;
    }

    public function cropper()
    {
        return self::imagick()
            ->resize()
            ->crop()
            ->save()
            ->end()
            ->getRelativeResizePath();
    }

    /**
     * @return Resizer
     * @throws \ImagickException
     */
    public function imagick(): self
    {
        if (!file_exists($this->file->tempName)) {
            throw new RuntimeException('File does not exist!');
        }
        $this->imagick = models\Resizer::imagick($this->file->tempName);

        return $this;
    }

    /**
     * @return Resizer
     */
    public function resize(): self
    {
        $result = models\Resizer::resize($this->imagick, $this->canvasData->width, $this->canvasData->height,
            $this->settings['blur'], $this->settings['bestfit']);

        if (!$result) {
            throw new RuntimeException('Fail to resize!');
        }

        return $this;
    }

    /**
     * @return Resizer
     */
    public function crop(): self
    {
        $result = models\Resizer::crop($this->imagick, $this->settings['size']['width'], $this->settings['size']['height'],
            -$this->canvasData->left, -$this->canvasData->top);

        if (!$result) {
            throw new RuntimeException('Fail to resize!');
        }

        return $this;
    }

    /**
     * @param int $width
     * @param int $height
     * @return string
     */
    public function createFileName(string $salt, int $width, int $height): string
    {
        $result = FileHelper::replacer('{salt}_{width}_{height}{:ifExist}.{format}', [
            '{salt}' => $salt,
            '{width}' => $width,
            '{height}' => $height,
            '{format}' => $this->settings['outputFormat']
        ], 0);

        return $result;
    }

    /**
     * @return string
     * @throws \yii\base\Exception
     */
    public function getResizeSavePath(): string
    {
        $resizeDir = FileHelper::replacer($this->unsafeDir . DS . $this->settings['relativeResizePath'],
            ['{client}' => $this->client]);
        FileHelper::createDirectory($resizeDir);

        return FileHelper::normalizePath($resizeDir . DS . $this->resizeFileName);
    }

    /**
     * @return string
     * @throws \yii\base\Exception
     */
    public function getOriginalSavePath(): string
    {
        $originalDir = FileHelper::replacer($this->unsafeDir . DS . $this->settings['relativeOriginalPath']);
        FileHelper::createDirectory($originalDir);

        return FileHelper::normalizePath($originalDir . DS . $this->originalFileName);
    }

    /**
     * @return Resizer
     */
    public function save(): self
    {
        if (!models\Resizer::save($this->imagick, $this->getResizeSavePath())) {
            throw new RuntimeException('Fail to save resize image');
        }

        if ($this->settings['saveOriginal']) {
            if (!copy($this->file->tempName, $this->getOriginalSavePath())) {
                throw new RuntimeException('Fail to save original image');
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getRelativeResizePath(): string
    {
        return FileHelper::replacer("/{$this->settings['relativeResizePath']}/{$this->resizeFileName}",
            ['{client}' => $this->client]);
    }

    public function end(): self
    {

    }
}