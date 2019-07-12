<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 19.11.2018
 * Time: 9:09
 */

namespace backend\behaviors;

use blog\fileManager\entities\DraftFilesSession;
use Exception;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class SaveDraftImagesBehavior extends Behavior
{
    public
        $contentAttribute = 'content';
    /**
     * @var DraftFilesSession
     */
    private $draftFilesSession;

    public function __construct(DraftFilesSession $draftFilesSession, $config = [])
    {
        parent::__construct($config);
        $this->draftFilesSession = $draftFilesSession;
    }

/*    static function collectData(Session $session, $data)
    {
        $oldData = $session->get('draftImages') ?? [];
        $session->set('draftImages', array_merge($data, $oldData));
    }*/

    /**
     * @return array
     */
    public function events(): array
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'moveDraftImages',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'moveDraftImages',
        ];
    }

    /**
     * @throws \yii\base\ErrorException
     */
    public function moveDraftImages($owner): void
    {
        $model = $owner->sender;

        try {
            $this->copyContent();
            $this->copyOriginal();
            $this->copyPost();
            $model->{$this->contentAttribute} = $this->replaceContent($model->{$this->contentAttribute});
        } catch (Exception $e) {
            $this->draftFilesSession->flushCache();
            print $e->getMessage();
            exit;
        }

        $this->draftFilesSession->flushCache();
    }

    private function replaceContent($content)
    {
        $replace = $this->draftFilesSession->getContentReplace();
        $draft = array_keys($replace);
        $save = array_values($replace);

        return str_replace($draft, $save, $content);
    }

    private function copyContent()
    {
        $this->copy($this->draftFilesSession->getContentCopy());
    }

    private function copyOriginal()
    {
        $this->copy($this->draftFilesSession->getOriginalCopy());
    }

    private function copyPost()
    {
        $this->copy($this->draftFilesSession->getPostCopy());
    }

    public function copy(array $files)
    {
        foreach ($files as $from => $to) {

            if (copy($from, $to)) {
                unlink($from);
            }
        }
    }
}