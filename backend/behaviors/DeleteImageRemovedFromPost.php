<?php


namespace backend\behaviors;

use Yii;
use blog\fileManager\entities\DraftFilesSession;
use blog\fileManager\entities\Path;
use blog\fileManager\transfers\FileTransfer;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;

/**
 * Class DeleteImageRemovedFromPost
 * @package backend\behaviors
 */
class DeleteImageRemovedFromPost extends Behavior
{
    public
        $sessionKey = 'oldPostImage',
        $imageAttribute = 'image_url',
        $contentAttribute = 'content';

    private
        $file,
        $uploadDir,
        $session;

    private $params;

    public function __construct(FileTransfer $file, DraftFilesSession $session, $config = [])
    {
        parent::__construct($config);
        $this->file = $file;
        $this->session = $session;
        $this->params = Yii::$app->params;

        /**
         * @var $uploadPath Path
         */
        $uploadPath = clone $this->params['resizer']['paths']['uploadDir'];
        $this->uploadDir = $uploadPath->convertAlias()->getPath();
    }

    public function events(): array
    {
        return [
            ActiveRecord::EVENT_BEFORE_UPDATE => 'fill',
            ActiveRecord::EVENT_AFTER_UPDATE => 'clear',
        ];
    }

    public function fill(Event $event): void
    {
        $entity = $event->sender;
        $this->fillPost($entity);
        $this->fillContent($entity);
    }

    public function clear(): void
    {
        if ($images = $this->session->get($this->sessionKey)) {
            foreach ($images as $image) {
                $image = (new Path($image))->toRelative()->concat($this->uploadDir);
                $this->file->delete($image->normalizePath()->getPath(), true);
            }
        }

        $this->session->flush($this->sessionKey);
    }

    public function fillPost($entity): void
    {
        $current = $entity->{$this->imageAttribute};
        $old = $entity->oldAttributes[$this->imageAttribute];

        if($current !== $old) {
            $this->session->push($this->sessionKey, [$old]);
        }
    }

    public function fillContent($entity): void
    {
        $current = $entity->{$this->contentAttribute};
        $old = $entity->oldAttributes[$this->contentAttribute];
        $host = str_replace(['/', '.'], ['\/', '\.'], $this->params['frontendHost']);
        $pattern = "~src ?= ?(?:\"|\')(?<urls>{$host}[\S]+\.[\S]+)(?:\"|\')~";

        if (preg_match_all($pattern, $old, $oldMatch) !== false) {
            $oldMatch = array_unique($oldMatch['urls']);

            preg_match_all($pattern, $current, $currentMatch);
            $currentMatch = array_unique($currentMatch['urls']);

            if (! empty($toDelete = array_diff($oldMatch, $currentMatch))) {
                $this->session->push($this->sessionKey, $toDelete);
            }
        }
    }
}