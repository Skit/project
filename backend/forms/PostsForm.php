<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 08.11.2018
 * Time: 21:44
 */

namespace backend\forms;


use backend\models\Categories;
use common\models\Forms;
use yii\db\Expression;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $image_url
 * @property string $video_url
 * @property string $content
 * @property string $preview
 * @property string $tags
 * @property string $meta_desc
 * @property string $meta_key
 * @property int $creator_id
 * @property int $category_id
 * @property int $count_view
 * @property int $is_highlight
 * @property int $is_active
 *
 * @property Categories $category
 */
class PostsForm extends Forms
{
    public $model;

    public
        $id,
        $title,
        $slug,
        $image_url,
        $video_url,
        $content,
        $preview,
        $tags,
        $meta_desc,
        $meta_key,
        $creator_id,
        $category_id,
        $created_at,
        $updated_at,
        $is_active;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'slug', 'category_id'], 'required'],
            [['content', 'preview'], 'string'],
            [['category_id', 'is_active', 'id', 'created_at'], 'integer'],
            [['title', 'slug', 'tags', 'meta_desc', 'meta_key'], 'string', 'max' => 255],
            [['image_url', 'video_url'], 'string', 'max' => 255],
            //[['title', 'slug', 'category_id', 'content', 'preview', 'is_active', 'tags', 'meta_desc', 'meta_key', 'image_url', 'video_url', 'created_at', 'updated_at', 'creator_id'], 'safe']
           /* [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class,
                'targetAttribute' => ['category_id' => 'id']],*/
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'slug' => 'Slug',
            'image_url' => 'Image Url',
            'video_url' => 'Video Url',
            'content' => 'Content',
            'preview' => 'Preview',
            'tags' => 'Tags',
            'meta_desc' => 'Meta Desc',
            'meta_key' => 'Meta Key',
            'creator_id' => 'Creator ID',
            'category_id' => 'Category ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'count_view' => 'Count View',
            'is_highlight' => 'Is Highlight',
            'is_active' => 'Is Active',
        ];
    }
}
