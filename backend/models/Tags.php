<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 03.11.2018
 * Time: 19:17
 */

namespace backend\models;

use Yii;
use yii\db\ActiveQuery;
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
    const
        DEFAULT_FREQUENCY = 1,
        IS_ACTIVE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
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
        $tag->frequency = self::DEFAULT_FREQUENCY;
        $tag->is_active = self::IS_ACTIVE;

        return $tag;
    }

    /**
     * @param string $name
     * @param string $slug
     * @param int $isActive
     */
    public function edit(string $name, string $slug, int $isActive): void
    {
        $this->name = $name;
        $this->slug = $slug;
        $this->is_active = $isActive;
    }

    public function frequencyUp(): void
    {
        $this->frequency++;
    }

    public function frequencyDown(): void
    {
        $this->frequency--;
    }

    /**
     * @return ActiveQuery
     */
    public function getPostsTags(): ActiveQuery
    {
        return $this->hasMany(PostsTags::class, ['tag_id' => 'id']);
    }
}