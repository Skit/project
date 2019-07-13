<?php


namespace blog\fileManager;


use blog\fileManager\entities\Coords;
use blog\fileManager\entities\Dimension;
use blog\fileManager\entities\DraftFilesSession;
use blog\fileManager\entities\ImagickSetUp;
use blog\fileManager\entities\Path;
use blog\fileManager\managers\ImagickManager;
use blog\fileManager\source\Image;
use blog\fileManager\transfers\FileTransfer;
use yii\base\Exception;

class ImageManager
{

    /**
     * @var FileTransfer
     */
    private $fileTransfer;

    /**
     * @var ImagickManager
     */
    private $imagickManager;
    /**
     * @var DraftFilesSession
     */
    private $draftFilesSession;


    public function __construct(ImagickManager $imagickManager, FileTransfer $fileTransfer, DraftFilesSession $draftFilesSession)
    {
        $this->imagickManager = $imagickManager;
        $this->fileTransfer = $fileTransfer;
        $this->draftFilesSession = $draftFilesSession;
    }

    /**
     * @param Image $image
     * @param ImagickSetUp $imagickSetUp
     * @param Path []$paths
     * @return array|string
     * @throws \ImagickException
     */
    public function imperaviResize(Image $image, ImagickSetUp $imagickSetUp, array $paths)
    {
        try {
            $resizer = $this->imagickManager
                ->init($imagickSetUp, $image)
                // FIXME it resize original too
                ->freeResize();

            $paths['saveDraft']->replacer(['{format}' => $imagickSetUp->format]);
            $paths['save']->replacer([
                '{width}' => $resizer->getResult()->getNewWidth(),
                '{height}' => $resizer->getResult()->getNewHeight(),
                '{format}' => $imagickSetUp->format
            ]);

            $paths['originalDraft']->replacer(['{format}' => $image->extension]);
            $paths['original']->replacer([
                '{width}' => $image->width,
                '{height}' => $image->height,
                '{format}' => $image->extension
            ]);

            $uploadDir = $paths['uploadDir']->getRaw();
            $originalDraft = $paths['originalDraft']->concat($uploadDir)->convertAlias()->normalizePath();
            $this->fileTransfer->createDir($originalDraft->getDir(), 0755, true);
            $this->fileTransfer->copy($image->tmpPath, $originalDraft->getPath());

            $original = $paths['original']->concat($uploadDir)->convertAlias()->normalizePath();
            $this->fileTransfer->createDir($original->getDir(), 0755, true);
            $this->draftFilesSession->setOriginalCopy($originalDraft->getPath(), $original->getPath());

            $saveDraft = $paths['saveDraft']->concat($uploadDir)->convertAlias()->normalizePath();
            $this->fileTransfer->createDir($saveDraft->getDir(), 0755, true);

            $save = $paths['save']->concat($uploadDir)->convertAlias()->normalizePath();
            $this->fileTransfer->createDir($save->getDir(), 0755, true);

            $this->draftFilesSession->setContentCopy($saveDraft->getPath(), $save->getPath());
            $this->draftFilesSession->setContentReplace($saveDraft->getUrl(), $save->getUrl());

            $resizer->save($saveDraft->getPath());
        } catch (Exception $e) {
            $this->draftFilesSession->flushCache();
            return $e->getMessage();
        }

        return $paths;
    }

    /**
     * @param Image $image
     * @param ImagickSetUp $imagickSetUp
     * // TODO записать в блог как сделать автодополнение для массива объектов
     * @param Path []$paths
     * @return mixed
     * @throws \ImagickException
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function postImageResize(Image $image, ImagickSetUp $imagickSetUp,
                                    Dimension $canvasDimension, Coords $canvasCoord, array $paths)
    {
        try {
            $resizer = $this->imagickManager
                ->init($imagickSetUp, $image)
                ->setDimension($canvasDimension)
                ->resize()
                ->setDimension($imagickSetUp->dimension)->setCoords($canvasCoord)
                ->crop();

            $paths['saveDraft']->replacer(['{format}' => $image->extension]);
            $paths['save']->replacer([
                '{width}' => $resizer->getResult()->getNewWidth(),
                '{height}' => $resizer->getResult()->getNewHeight(),
                '{format}' => $imagickSetUp->format
            ]);

            $paths['originalDraft']->replacer(['{format}' => $image->extension]);
            $paths['original']->replacer([
                '{width}' => $image->width,
                '{height}' => $image->height,
                '{format}' => $image->extension
            ]);

            $uploadDir = $paths['uploadDir']->getRaw();
            $originalDraft = $paths['originalDraft']->concat($uploadDir)->convertAlias()->normalizePath();
            $this->fileTransfer->createDir($originalDraft->getDir(), 0755, true);
            $this->fileTransfer->copy($image->tmpPath, $originalDraft->getPath());

            $original = $paths['original']->concat($uploadDir)->convertAlias()->normalizePath();
            $this->fileTransfer->createDir($original->getDir(), 0755, true);
            $this->draftFilesSession->setOriginalCopy($originalDraft->getPath(), $original->getPath());

            $saveDraft = $paths['saveDraft']->concat($uploadDir)->convertAlias()->normalizePath();
            $this->fileTransfer->createDir($saveDraft->getDir(), 0755, true);
            $resizer->save($saveDraft->getPath());

            $save = $paths['save']->concat($uploadDir)->convertAlias()->normalizePath();
            $this->fileTransfer->createDir($save->getDir(), 0755, true);
            $this->draftFilesSession->setPostCopy($saveDraft->getPath(), $save->getPath());
        } catch (Exception $e) {
            $this->draftFilesSession->flushCache();
            return $e->getMessage();
        }

        return $save->getUrl();
    }
}