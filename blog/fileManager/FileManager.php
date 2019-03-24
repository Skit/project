<?php


namespace blog\fileManager;


use blog\fileManager\source\FileSourceInterface;

class FileManager
{
    public function source(FileSourceInterface $source)
    {
        return $this;
    }
}