<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 03.11.2018
 * Time: 19:17
 */

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class Tags
 *
 * @package blog\entities
 * @property integer id
 * @property string name
 * @property string slug
 * @property integer frequency
 * @property integer is_active
 */
class Tags extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tags}}';
    }

    /**
     * @param string $name
     * @param string $slug
     * @return Tags
     */
    public static function create(string $name, string $slug): self
    {
        $tag = new self();
        $tag->name = $name;
        $tag->slug = $slug;

        return $tag;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostsTags()
    {
        return $this->hasMany(PostsTags::class, ['tag_id' => 'id']);
    }
}