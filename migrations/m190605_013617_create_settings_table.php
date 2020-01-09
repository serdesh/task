<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%settings}}`.
 */
class m190605_013617_create_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%settings}}', [
            'id' => $this->primaryKey(),
            'key' => $this->string(),
            'value' => $this->string(),
            'name' => $this->string(),
            'description' => $this->text(),
        ]);

        $this->batchInsert('{{%settings}}', ['key', 'name'],[
                ['login_yandex', 'Логин Яндекса'],
                ['password_yandex', 'Пароль Яндекса'],
                ['login_google', 'Логин Google'],
                ['password_google', 'Пароль Google'],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%settings}}');
    }
}
