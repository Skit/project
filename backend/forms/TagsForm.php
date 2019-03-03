<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 03.11.2018
 * Time: 18:37
 */

namespace backend\forms;

use backend\models\Tags;
use \common\validators\SlugValidator;
use yii\base\Model;

class TagsForm extends Model
{
    public
        $name,
        $slug,
        $frequency,
        $is_active;

/*    private $_tag;

    public function __construct(Tags $tag = null, $config = [])
    {
        if ($tag) {
            $this->name = $tag->name;
            $this->slug = $tag->slug;
            $this->_tag = $tag;
        }
        parent::__construct($config);
    }*/

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['name', 'slug'], 'required'],
            [['name', 'slug'], 'string', 'max' => 50],
            ['slug', SlugValidator::class],
/*            [['name', 'slug'], 'unique', 'targetClass' => Tags::class,
                'filter' => $this->_tag ? ['<>', 'id', $this->_tag->id] : null]*/
        ];
    }

    /**
     *
     */
    public function afterValidate()
    {
        $this->frequency = Tags::DEFAULT_FREQUENCY;
        $this->is_active = Tags::DEFAULT_IS_ACTIVE;

        return parent::afterValidate();
    }
}