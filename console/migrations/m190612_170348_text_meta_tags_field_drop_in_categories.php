<?php

use yii\db\Migration;
use backend\models\Categories;

/**
 * Class m190612_170348_text_meta_tags_field_drop_in_categories
 */
class m190612_170348_text_meta_tags_field_drop_in_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn(Categories::tableName(), 'meta_desc');
        $this->dropColumn(Categories::tableName(), 'meta_key');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn(Categories::tableName(), 'meta_desc',
            $this->string(255)->after('tags'));

        $this->addColumn(Categories::tableName(), 'meta_key',
            $this->string(255)->after('tags'));
    }

}
