<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m240220_061917_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'fullname' => $this->string()->notNull(),
            'position' => $this->string()->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'verification_token' => $this->string()->defaultValue(null),
            'email' => $this->string()->notNull()->unique(),
            'role' =>  $this->string()->defaultValue(null),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
            'flag_del' => $this->integer()->notNull()->defaultValue(0),
        ], $tableOptions);

        $security = Yii::$app->security;
        $this->batchInsert(
            'user',
            ['username', 'fullname', 'position', 'auth_key', 'password_hash', 'email', 'status', 'created_at'],
            [
                ['admin', 'Administrator', 'Admin', $security->generateRandomString(), $security->generatePasswordHash('admin'), 'admin@admin.com', 10, date("Y-m-d H:i:s")],
                ['demo', 'Demostration', 'Tester', $security->generateRandomString(), $security->generatePasswordHash('demo'), 'demo@admin.com', 10, date("Y-m-d H:i:s")],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
