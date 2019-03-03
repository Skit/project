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
    const
        DEFAULT_IS_ACTIVE = 1,
        DEFAULT_FREQUENCY = 1;

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
     * Set rows as [[name=>tagName, slug=>tagSlug]]
     *
     * @param array $rows
     * @return int
     * @throws \yii\db\Exception
     */
    public static function batchCreate(array $rows): int
    {
        return Yii::$app->db->createCommand()
            ->batchInsert(self::tableName(), ['name', 'slug'], $rows)
            ->execute();
    }

    /**
     * @param int $value
     * @return int
     */
    public function updateFrequency(int $value=1): int
    {
        return $this->updateCounters(['frequency' => $value]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostsTags()
    {
        return $this->hasMany(PostsTags::class, ['tag_id' => 'id']);
    }
}