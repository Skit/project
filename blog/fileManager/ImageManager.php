<?php


namespace blog\fileManager;


use blog\fileManager\entities\DraftFilesSession;
use blog\fileManager\entities\ImagickSetUp;
use blog\fileManager\entities\Paths;
use blog\fileManager\managers\ImagickManager;
use blog\fileManager\source\Image;
use blog\fileManager\transfers\FileTransfer;

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

    public function imperaviResize(Image $image, ImagickSetUp $imagickSetUp, Paths $paths)
    {
        $resizer = $this->imagickManager
            ->setOrigin($image)
            ->setUp($imagickSetUp)
            ->freeResize();

        $paths->replaceSave([
            '{width}' => $resizer->getWidth(),
            '{height}' => $resizer->getHeight(),
            '{format}' => $imagickSetUp->format
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

        $resizer->strip()->save($paths->getAbsoluteDraft());

        return [
            'filelink' => $paths->getDraftSave()->getSiteUrl(),
            'filename' => $paths->getSave()->fileName(),
        ];
    }

    public function postImageResize(Image $image, ImagickSetUp $imagickSetUp, Paths $paths)
    {
        $resizer = $this->imagickManager
            ->setOrigin($image)
            ->setUp($imagickSetUp)
            ->resize()
            ->crop();

        $paths->replaceSave([
            '{width}' => $resizer->getWidth(),
            '{height}' => $resizer->getHeight(),
            '{format}' => $imagickSetUp->format
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
        $this->fileTransfer->createDir($paths->getOriginalDraftDir(), 0755, true);
        $this->fileTransfer->createDir($paths->getAbsoluteSaveDir(), 0755, true);
        $this->draftFilesSession->setOriginalCopy($paths->getOriginalDraft(), $paths->getAbsoluteOriginal());
        $this->draftFilesSession->setPostCopy($paths->getAbsoluteDraft(), $paths->getAbsoluteSave());

        $resizer->strip()->save($paths->getAbsoluteDraft());

        return $paths->getOriginal()->raw();
    }
}