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

    public function setPath(string $savePath): void
    {
        $this->savePath = $savePath;
    }

    public function setResult(bool $result): void
    {
        $this->result = $result;
    }

    public function setNewWidth(int $newWidth): void
    {
        $this->newWidth = $newWidth;
    }

    public function setNewHeight(int $newHeight): void
    {
        $this->newHeight = $newHeight;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
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

}