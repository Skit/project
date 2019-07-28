<?php

namespace backend\models;

use backend\behaviors\DeleteImageRemovedFromPost;
use backend\behaviors\ImperaviBugFixBehavior;
use backend\behaviors\MetaTagsBehavior;
use backend\behaviors\PostTagsBehavior;
use backend\behaviors\SaveDraftImagesBehavior;
use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%posts}}".
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $image_url
 * @property string $video_url
 * @property string $content
 * @property string $preview
 * @property string $tags
 * @property string $meta_tags
 * @property int $creator_id
 * @property int $category_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $published_at
 * @property int $count_view
 * @property int $is_highlight
 * @property int $is_active
 *
 * @property Categories $category
 * @property PostsTags[] $postsTags
 */
class Posts extends ActiveRecord
{

    private
        /*
         * @var array
         */
        $_linkTags = [],
        /**
         * @var array
         */
        $_unlinkTags = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%posts}}';
    }

    /**
     * @param Model $form
     * @return Posts
     */
    public static function create(Model $form): self
    {
        $post = new static();
        $post->setAttributes($form->getAttributes(), false);
        $post->creator_id = Yii::$app->user->identity->id;

        return $post;
    }

    /**
     * @param Model $form
     * @param self $post
     */
    public static function edit(Model $form, self $post): void
    {
        $post->setAttributes($form->getAttributes(), false);
    }

    /**
     * @param $tagId
     */
    public function addLinkTag($tagId): void
    {
        $this->_linkTags[] = $tagId;
    }

    /**
     * @param $tagId
     */
    public function addUnlinkTag($tagId): void
    {
        $this->_unlinkTags[] = $tagId;
    }

    /**
     * @param $tagId
     */
    public function getLinkTags(): array
    {
        return $this->_linkTags;
    }

    /**
     * @param $tagId
     */
    public function getUnlinkTags(): array
    {
        return $this->_unlinkTags;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Categories::class, ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostsTags(): ActiveQuery
    {
        return $this->hasMany(PostsTags::class, ['post_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
            SaveDraftImagesBehavior::class,
            ImperaviBugFixBehavior::class,
            MetaTagsBehavior::class,
            PostTagsBehavior::class,
            DeleteImageRemovedFromPost::class,
        ];
    }

   /*TODO запостить метод imgToEncode64
   public function afterFind()
    {
        $i = new \Imagick($this->image_url);
        $this->image_url = 'data:image/jpeg;base64,' . base64_encode($i->getImageBlob());
        parent::afterFind();
    }*/

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $publishedTime = $this->published_at ? strtotime($this->published_at) : time();
        $this->setAttribute('published_at', $publishedTime + rand(300, 3000));
        $this->setAttribute('is_highlight', $this->highlightCheck());

        return parent::beforeSave($insert);
    }

    private function highlightCheck(): int
    {
        return preg_match(
            '~\<pre\>[\s\r\n]*\<code +class=(?:\"|\')[\w]+(?:\"|\') ?\>(.*)\<\/code\>[\s\r\n]*<\/pre\>~is',
            $this->content
        );
    }
}
