<?php

use yii\db\Migration;

/**
 * Class m190310_141515_meta_tags_json_in_posts_tbl
 */
class m190310_141515_meta_tags_json_in_posts_tbl extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('{{%posts}}', 'meta_tags',
            $this->json()->after('tags'));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('{{%posts}}', 'meta_tags');
    }

}
