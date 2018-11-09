<?php

use yii\db\Migration;
use \common\models\User;

/**
 * Class m181109_064519_insert_system_user
 */
class m181109_064519_insert_system_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $user = new User();
        $user->username = 'system';
        $user->email = 'system@system.loc';
        $user->can_login = 0;
        $user->status = $user::STATUS_SYSTEM;
        $user->setPassword($user->email);
        $user->generateAuthKey();
        $user->save();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $user = User::findOne(1);
        $user->delete();
    }
}
