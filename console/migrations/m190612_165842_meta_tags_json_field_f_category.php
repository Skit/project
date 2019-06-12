<?php

use yii\db\Migration;
use backend\models\Categories;

/**
 * Class m190612_165842_meta_tags_json_field_f_category
 */
class m190612_165842_meta_tags_json_field_f_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Categories::tableName(), 'meta_tags',
            $this->json()->after('description'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Categories::tableName(), 'meta_tags');
    }

}
