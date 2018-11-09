<?php

use yii\db\Migration;

/**
 * Class m181109_064000_null_to_update_at_time_user
 */
class m181109_064000_null_to_update_at_time_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%user}}', 'created_at', $this->integer(11)->unsigned()->notNull());
        $this->alterColumn('{{%user}}', 'updated_at', $this->integer(11)->unsigned());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%user}}', 'created_at', $this->integer(11)->notNull());
        $this->alterColumn('{{%user}}', 'updated_at', $this->integer()->notNull());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181109_064000_null_to_update_at_time_user cannot be reverted.\n";

        return false;
    }
    */
}
