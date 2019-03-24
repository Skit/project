<?php


namespace blog\fileManager\entities;

class Paths
{
    /**
     * @var Path
     */
    private
        $_savePath,
        $_originalPath,
        $_draftSave,
        $_draftOriginal,
        $_uploadDir;
    

    public function __construct(string $savePath, string $draftSave, string $originalPath,
                                string $draftOriginal, string $uploadDir)
    {
        $this->_savePath = new Path($savePath);
        $this->_originalPath = new Path($originalPath);
        $this->_draftSave = new Path($draftSave);
        $this->_uploadDir = new Path($uploadDir);
        $this->_draftOriginal = new Path($draftOriginal);
    }

    public function replaceSave(array $replaceData = [])
    {
        $this->_savePath = new Path($this->_savePath->replacer($replaceData));
    }

    public function replaceOriginal(array $replaceData = [])
    {
        $this->_originalPath = new Path($this->_originalPath->replacer($replaceData));
    }
    public function replaceDraftSave(array $replaceData = [])
    {
        $this->_draftSave = new Path($this->_draftSave->replacer($replaceData));
    }

    public function replaceDraftOriginal(array $replaceData = [])
    {
        $this->_draftOriginal = new Path($this->_draftOriginal->replacer($replaceData));
    }

    public function getAbsoluteOriginal(): string
    {
        return "{$this->getUpload()->convertAlias()}/{$this->getOriginal()->raw()}";
    }

    public function getAbsoluteDraft(): string
    {
        return "{$this->getUpload()->convertAlias()}/{$this->getDraftSave()->raw()}";
    }

    public function getOriginalDraft(): string
    {
        return "{$this->getUpload()->convertAlias()}/{$this->getDraftOriginal()->raw()}";
    }

    public function getAbsoluteDraftDir(): string
    {
        return "{$this->getUpload()->convertAlias()}/{$this->getDraftSave()->getDir()}";
    }

    public function getOriginalDraftDir(): string
    {
        return "{$this->getOriginal()->convertAlias()}/{$this->getDraftOriginal()->getDir()}";
    }

    public function getAbsoluteSaveDir()
    {
        return "{$this->getUpload()->convertAlias()}/{$this->getSave()->getDir()}";
    }

    public function getAbsoluteSave()
    {
        return "{$this->getUpload()->convertAlias()}/{$this->getSave()->raw()}";
    }

    /**
     * @return Path
     */
    public function getSave(): Path
    {
        return $this->_savePath;
    }

    /**
     * @return Path
     */
    public function getOriginal(): Path
    {
        return $this->_originalPath;
    }

    /**
     * @return Path
     */
    public function getDraftSave(): Path
    {
        return $this->_draftSave;
    }

    /**
     * @return Path
     */
    public function getDraftOriginal(): Path
    {
        return $this->_draftOriginal;
    }

    /**
     * @return Path
     */
    public function getUpload(): Path
    {
        return $this->_uploadDir;
    }


}