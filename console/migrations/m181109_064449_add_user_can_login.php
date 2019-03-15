<?php

use yii\db\Migration;

/**
 * Class m181109_064449_add_user_can_login
 */
class m181109_064449_add_user_can_login extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'can_login',
            $this->smallInteger(1)->notNull()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'can_login');
    }
}
