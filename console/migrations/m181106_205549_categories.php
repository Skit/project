<?php

use yii\db\Migration;

/**
 * Class m181106_205549_categories
 */
class m181106_205549_categories extends Migration
{
    private $table = '{{%categories}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable($this->table, [
            'id' => $this->primaryKey()->unsigned(),
            'title' => $this->string(255)->notNull(),
            'slug' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'meta_desc' => $this->string(255),
            'meta_key' => $this->string(255),
            'creator_id' => $this->integer()->unsigned()->notNull(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned(),
            'is_active' => $this->smallInteger(1)->notNull()->defaultValue(1),
        ], $tableOptions);

        Yii::$app->db->createCommand()->insert($this->table, [
            'title' => 'Default',
            'slug' => 'default',
            'creator_id' => 0,
            'created_at' => time(),
        ])->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%categories}}');
    }
}
