<?php

use yii\db\Migration;

/**
 * Class m190310_142113_meta_field_drop_in_posts_tbl
 */
class m190310_142113_meta_field_drop_in_posts_tbl extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%posts}}', 'meta_desc');
        $this->dropColumn('{{%posts}}', 'meta_key');
    }

    public function down()
    {
        $this->addColumn('{{%posts}}', 'meta_desc',
            $this->string(255)->after('tags'));

        $this->addColumn('{{%posts}}', 'meta_key',
            $this->string(255)->after('tags'));
    }

}
