<?php


namespace blog\fileManager\entities;


use yii\web\Session;

class DraftFilesSession
{
    /**
     * @var Session
     */
    private $session;

    private
        $postKey = 'post_draft',
        $copyOriginalKey = 'original_key',
        $copyContentKey = 'copy_content_draft',
        $replaceContentKey = 'replace_content_draft';

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getContentCopy(): array
    {
        return $this->session->get($this->copyContentKey) ?? [];
    }

    public function getContentReplace(): array
    {
        return $this->session->get($this->replaceContentKey) ?? [];
    }

    public function getPostCopy(): array
    {
        return $this->session->get($this->postKey) ?? [];
    }

    public function getOriginalCopy(): array
    {
        return $this->session->get($this->copyOriginalKey) ?? [];
    }

    public function setContentCopy(string $draft, string $target)
    {
        $this->push($this->copyContentKey, [$draft => $target]);
    }

    public function setContentReplace(string $draft, string $target)
    {
        $this->push($this->replaceContentKey, [$draft => $target]);
    }

    public function setOriginalCopy(string $draft, string $target)
    {
        $this->push($this->copyOriginalKey, [$draft => $target]);
    }

    public function setPostCopy(string $draft, string $target)
    {
        $this->session->set($this->postKey, [$draft => $target]);
    }

    public function push(string $key, array $data)
    {
        $oldData = $this->session->get($key) ?? [];
        $this->session->set($key, array_merge($data, $oldData));
    }

    public function add(string $key, $data)
    {
        $this->session->set($key, $data);
    }

    public function get(string $key)
    {
        return $this->session->get($key);
    }

    public function flushCache()
    {
        $this->flush($this->postKey);
        $this->flush($this->copyOriginalKey);
        $this->flush($this->copyContentKey);
        $this->flush($this->replaceContentKey);
    }

    public function flush(string $key)
    {
        $this->session->set($key, null);
    }
}