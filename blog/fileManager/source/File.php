<?php


namespace blog\fileManager\source;


class File
{
    public
        $extension,
        $originalName,
        $name,
        $tmpPath,
        $mimeType,
        $size;

    public function __construct(string $name, string $tmpPath, string $size, string $mimeType)
    {
        $this->name = $name;
        $this->tmpPath = $tmpPath;
        $this->size = $size;
        $this->mimeType = $mimeType;

        $this->baseInfo();
    }

    protected function baseInfo()
    {
        $originalData = pathinfo($this->name);
        $this->extension = $originalData['extension'] ?? '';
        $this->originalName = $originalData['filename'];
    }
}