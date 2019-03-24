<?php


namespace blog\fileManager\entities;

use blog\fileManager\services\FileService;
use Yii;

class Path
{
    /**
     * @var string
     */
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function raw(): string
    {
        return $this->path;
    }

    public function convertAlias(): string
    {
        return Yii::getAlias($this->raw());
    }

    public function replacer(array $replaceData = []): string
    {
        return Yii::$container->invoke(function($path, FileService $service) use ($replaceData) {
            return $service->pathReplacer($path, $replaceData);
        }, ['path' => $this->path]);
    }

    public function getSiteUrl()
    {
        return Yii::$app->params['baseUrl'] . '/' . $this->raw();
    }

    public function getDir(): string
    {
        return rtrim(str_replace($this->fileName(), '', $this->path), '/');
    }

    public function fileName(): string
    {
        return basename($this->path);
    }
}