<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%boss}}`.
 */
class m190408_102022_create_boss_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%boss}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'messenger_id' => $this->integer()->comment('Наименование мессенджера'),
            'messenger_number' => $this->string()->comment('Номер мессенджера'),
            'notes' => $this->text(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%boss}}');
    }
}
