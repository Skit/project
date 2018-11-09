<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 03.11.2018
 * Time: 18:37
 */

namespace backend\forms;

use \common\validators\SlugValidator;
use yii\base\Model;

class TagsForm extends Model
{
    public
        $name,
        $slug;
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
}