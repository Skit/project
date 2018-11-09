<?php

use yii\db\Migration;

/**
 * Class m181106_205857_post
 */
class m181106_205857_post extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%posts}}', [
            'id' => $this->primaryKey()->unsigned(),
            'title' => $this->string(255)->notNull(),
            'slug' => $this->string(255)->notNull(),
            'image_url' => $this->string(255),
            'video_url' => $this->string(255),
            'content' => $this->text(),
            'preview' => $this->text(),
            'tags' => $this->string(255),
            'meta_desc' => $this->string(255),
            'meta_key' => $this->string(255),
            'creator_id' => $this->integer(11)->unsigned()->notNull(),
            'category_id' => $this->integer()->unsigned(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
            'count_view' => $this->integer()->unsigned()->defaultValue(0),
            'is_highlight' => $this->smallInteger(1)->defaultValue(0),
            'is_active' => $this->smallInteger(1)->notNull()->defaultValue(1),
        ], $tableOptions);

        $this->createIndex(
            'idx_posts_categories',
            '{{%posts}}',
            'category_id'
        );

        $this->addForeignKey(
            'fk_posts_categories',
            '{{%posts}}',
            'category_id',
            '{{%categories}}',
            'id',
            'RESTRICT','RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk_posts_categories',
            '{{%posts}}'
        );

        $this->dropIndex(
            'idx_posts_categories',
            '{{%posts}}'
        );

        $this->dropTable('{{%posts}}');
    }
}
