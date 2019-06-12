<?php


namespace blog\fileManager\transfers;


class FileTransfer
{
    public function createDir(string $path, int $mode = 0755, bool $recursive = false): bool
    {
        if(! is_dir($path)) {
            if(! mkdir($path, $mode, $recursive)) {
                throw new \DomainException("Fail to create folder path: {$path}");
            }
        }

        return true;
    }

    public function copy(string $from, string $to)
    {
        copy($from, $to);
    }
}