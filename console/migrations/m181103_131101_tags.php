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
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%tags}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(50)->notNull()->unique(),
            'slug' => $this->string(50)->notNull()->unique(),
            'frequency' => $this->smallInteger()->unsigned()->defaultValue(1),
            'is_active' => $this->smallInteger(1)->defaultValue(1),
        ], $tableOptions);
    }
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%tags}}');
    }
}
