<?php

use backend\models\Posts;
use yii\db\Migration;

/**
 * Class m190330_062407_published_at_in_post_tbl
 */
class m190330_062407_published_at_in_post_tbl extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            Posts::tableName(),
            'published_at',
            $this->integer(11)->unsigned()->defaultValue(null)->after('created_at'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Posts::tableName(), 'published_at');
    }

}
