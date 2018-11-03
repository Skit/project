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
            'frequency' => $this->smallInteger()->unsigned()->defaultValue(1),
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
}
