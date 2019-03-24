<?php


namespace blog\fileManager;


use blog\fileManager\entities\DraftFilesSession;
use blog\fileManager\entities\JpegSetUp;
use blog\fileManager\entities\Paths;
use blog\fileManager\managers\ImagickManager;
use blog\fileManager\source\Image;
use blog\fileManager\transfers\FileTransfer;
use yii\web\Session;

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
     * @var Session
     */
    private $session;
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

    public function imperaviResize(Image $image, JpegSetUp $fileSetUp, Paths $paths)
    {
        $resizer = $this->imagickManager
            ->setOrigin($image)
            ->setUpJpeg($fileSetUp)
            ->strip()
            ->freeResize();

        $paths->replaceSave([
            '{width}' => $resizer->getWidth(),
            '{height}' => $resizer->getHeight(),
            '{format}' => $fileSetUp->format
        ]);
        $paths->replaceDraftSave([
            '{format}' => $image->extension
        ]);
        $paths->replaceOriginal([
            '{width}' => $image->width,
            '{height}' => $image->height,
            '{format}' => $image->extension
        ]);
        $paths->replaceDraftOriginal([
            '{format}' => $image->extension
        ]);

        $this->fileTransfer->copy($image->tmpPath, $paths->getOriginalDraft());
        $this->fileTransfer->createDir($paths->getAbsoluteDraftDir(), 0755, true);
        $this->fileTransfer->createDir($paths->getAbsoluteSaveDir(), 0755, true);
        $this->fileTransfer->createDir($paths->getOriginalDraftDir(), 0755, true);
        $this->draftFilesSession->setOriginalCopy($paths->getOriginalDraft(), $paths->getAbsoluteOriginal());
        $this->draftFilesSession->setContentCopy($paths->getAbsoluteDraft(), $paths->getAbsoluteSave());
        $this->draftFilesSession->setContentReplace($paths->getDraftSave()->getSiteUrl(), $paths->getSave()->getSiteUrl());

        $resizer->save($paths->getAbsoluteDraft());

        return [
            'filelink' => $paths->getDraftSave()->getSiteUrl(),
            'filename' => $paths->getSave()->fileName(),
        ];
    }
}