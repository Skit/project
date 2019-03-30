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
        $this->sessionRestore($this->copyContentKey, [$draft => $target]);
    }

    public function setContentReplace(string $draft, string $target)
    {
        $this->sessionRestore($this->replaceContentKey, [$draft => $target]);
    }

    public function setOriginalCopy(string $draft, string $target)
    {
        $this->sessionRestore($this->copyOriginalKey, [$draft => $target]);
    }

    public function setPostCopy(string $draft, string $target)
    {
        $this->session->set($this->postKey, [$draft => $target]);
    }

    private function sessionRestore(string $key, $data)
    {
        $oldData = $this->session->get($key) ?? [];
        $this->session->set($key, array_merge($data, $oldData));
    }

    public function flushCache()
    {
        $this->session->set($this->postKey, null);
        $this->session->set($this->copyOriginalKey, null);
        $this->session->set($this->copyContentKey, null);
        $this->session->set($this->replaceContentKey, null);
    }
}