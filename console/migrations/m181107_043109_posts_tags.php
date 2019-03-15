<?php

use yii\db\Migration;

/**
 * Class m181107_043109_posts_tags
 */
class m181107_043109_posts_tags extends Migration
{
    public
        $tableName = 'posts_tags',
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

    /**
     * {@inheritdoc}
     */
    public function Up()
    {
        $this->createTable("{{%{$this->tableName}}}", [
            'id' => $this->primaryKey(),
            'tag_id' => $this->integer(11)->unsigned()->notNull(),
            'post_id' => $this->integer(11)->unsigned()->notNull(),
        ], $this->tableOptions);

        //$this->addPrimaryKey('{{%pk-posts_tags}}', "{{%{$this->tableName}}}", ['post_id', 'tag_id']);

        $this->createIndex('{{%idx_post_tags_tag_id}}',
            "{{%{$this->tableName}}}",
            'tag_id'
        );

        $this->addForeignKey('{{%fk_post_tags_tag_id}}',
            "{{%{$this->tableName}}}",
            'tag_id','{{%tags}}','id',
            'CASCADE','CASCADE'
        );

        $this->createIndex('{{%idx_posts_tags_post_id}}',
            "{{%{$this->tableName}}}",
            'post_id'
        );

        $this->addForeignKey('{{%fk_posts_tags_post_id}}',
            "{{%{$this->tableName}}}",
            'post_id','{{%posts}}','id',
            'CASCADE','CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            '{{%fk_posts_tags_post_id}}',
            "{{%{$this->tableName}}}"
        );

        $this->dropIndex(
            '{{%idx_posts_tags_post_id}}',
            "{{%{$this->tableName}}}"
        );

        $this->dropForeignKey(
            '{{%fk_post_tags_tag_id}}',
            "{{%{$this->tableName}}}"
        );

        $this->dropIndex(
            '{{%idx_post_tags_tag_id}}',
            "{{%{$this->tableName}}}"
        );

        $this->dropTable("{{%{$this->tableName}}}");
    }
}
