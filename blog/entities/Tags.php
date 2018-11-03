<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 03.11.2018
 * Time: 16:06
 */

namespace blog\entities;

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

    public static function create(string $name, string $slug): self
    {
        $tag = new self();
        $tag->name = $name;
        $tag->slug = $slug;
        $tag->frequency = self::DEFAULT_FREQUENCY;
        $tag->is_active = self::DEFAULT_IS_ACTIVE;
        return $tag;
    }
}