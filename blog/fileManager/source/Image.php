<?php


namespace blog\fileManager\source;


class Image extends File
{
    public
        $mimeType,
        $width,
        $height,
        $scale;

    public function __construct(string $name, string $tmpPath, string $size, string $mimeType)
    {
        parent::__construct($name, $tmpPath, $size, $mimeType);

        $this->dimensions();
        $this->scaleType();
        $this->mimeType();
    }

    protected function dimensions(): void
    {
        $imageData = getimagesize($this->tmpPath);
        $this->width = $imageData[0];
        $this->height = $imageData[1];
    }

    protected function scaleType(): void
    {
        $this->scale = $this->width > $this->height ? 'landscape' : 'portrait';
    }

    protected function mimeType()
    {
        if(($mime = exif_imagetype($this->tmpPath)) !== false) {
            $this->mimeType = image_type_to_mime_type($mime);
        }
    }
}