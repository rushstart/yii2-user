<?php

use yii\db\Migration;

/**
 * Class m200123_134832_create_table_user
 */
class m200123_134832_create_table_user extends Migration
{
    /**
     * {@inheritdoc}
     * @throws \yii\base\Exception
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'logged_in_at' => $this->integer(),
        ]);
        $this->createTable('user_account', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'source' => $this->string(32)->notNull(),
            'source_id' => $this->string(),
            'source_token' => $this->string()->notNull(),
            'properties' => $this->json(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('fk-account-user_id-user-id', 'user_account', 'user_id', 'user', 'id', 'CASCADE');
        $this->addForeignKey('fk-user-logged_in_source-account-id', 'user', 'logged_in_source', 'user_account', 'id');
        $this->createIndex('source', 'user_account', ['source', 'source_id', 'source_token'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-account-user_id-user-id', 'user_account');
        $this->dropForeignKey('fk-user-logged_in_source-account-id', 'user');
        $this->dropTable('user_account');
        $this->dropTable('user');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200123_134832_create_table_user cannot be reverted.\n";

        return false;
    }
    */
}
