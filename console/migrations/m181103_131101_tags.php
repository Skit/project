<?php

use yii\db\Migration;

/**
 * Class m181103_131101_tags
 */
class m181103_131101_tags extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tags}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(50)->notNull()->unique(),
            'slug' => $this->string(50)->notNull(),
            'frequency' => $this->smallInteger()->notNull()->unsigned()->defaultValue(1),
            'is_active' => $this->smallInteger(1)->unsigned()->defaultValue(1),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%tags}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181103_131101_tags cannot be reverted.\n";

        return false;
    }
    */
}
