<?php


namespace blog\fileManager\entities;


class ImagickResult
{
    /**
     * @var bool
     */
    private $result;
    /**
     * @var int
     */
    private $newWidth;
    /**
     * @var int
     */
    private $newHeight;
    /**
     * @var string
     */
    private $savePath;
    /**
     * @var int
     */
    private $oldWidth;
    /**
     * @var int
     */
    private $oldHeight;
    /**
     * @var string
     */
    private $scale;
    /**
     * @var string
     */
    private $originalName;
    /**
     * @var string
     */
    private $newName;
    /**
     * @var string
     */
    private $extension;

    public function __construct(bool $result, string $savePath, int $newWidth, int $newHeight, string $scale,
                                int $oldWidth, int $oldHeight, string $originalName, string $extension)
    {
        $this->result = $result;
        $this->newWidth = $newWidth;
        $this->newHeight = $newHeight;
        $this->savePath = $savePath;
        $this->oldWidth = $oldWidth;
        $this->oldHeight = $oldHeight;
        $this->scale = $scale;
        $this->originalName = $originalName;
        $this->extension = $extension;
    }

    /**
     * @return bool
     */
    public function isResult(): bool
    {
        return $this->result;
    }

    /**
     * @return int
     */
    public function getNewWidth(): int
    {
        return $this->newWidth;
    }

    /**
     * @return int
     */
    public function getNewHeight(): int
    {
        return $this->newHeight;
    }

    /**
     * @return string
     */
    public function getSavePath(): string
    {
        return $this->savePath;
    }

    /**
     * @return int
     */
    public function getOldWidth(): int
    {
        return $this->oldWidth;
    }

    /**
     * @return int
     */
    public function getOldHeight(): int
    {
        return $this->oldHeight;
    }

    /**
     * @return string
     */
    public function getScale(): string
    {
        return $this->scale;
    }

    /**
     * @return string
     */
    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    /**
     * @return string
     */
    public function getNewName(): string
    {
        return $this->newName;
    }

    /**
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

}