<?php

use yii\db\Migration;
use \backend\models\Categories;

/**
 * Class m181109_194054_insert_default_category
 */
class m181109_194054_insert_default_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $categories = new Categories();
        $categories->title = 'Default';
        $categories->slug = 'Default';
        $categories->description = 'Default category';
        $categories->meta_desc = 'Default category';
        $categories->meta_key = 'Default category';
        $categories->creator_id = \common\models\User::SYSTEM_USER_ID;
        $categories->is_active = $categories::ACTIVE;
        $categories->save();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $category = Categories::findOne(1);
        $category->delete();
    }
}
