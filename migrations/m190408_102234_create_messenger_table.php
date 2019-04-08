<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%messenger}}`.
 */
class m190408_102234_create_messenger_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%messenger}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()
        ]);

        $this->insert('{{%messenger}}', [
            'name' => 'Telegram'
        ]);
        $this->insert('{{%messenger}}', [
            'name' => 'Viber'
        ]);
        $this->insert('{{%messenger}}', [
            'name' => 'WhatsApp'
        ]);
        $this->insert('{{%messenger}}', [
            'name' => 'VK'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%messenger}}');
    }
}
