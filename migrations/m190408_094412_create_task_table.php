<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task}}`.
 */
class m190408_094412_create_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task}}', [
            'id' => $this->primaryKey(),
            'description' => $this->text(),
            'start' => $this->timestamp()->comment('Начало выполнения (текущий период)')->defaultValue(null),
            'all_time' => $this->time(2)->comment('Общее время выполнения')->defaultValue('00:00'),
            'status' => $this->integer()->comment('Завершено/В работе'),
            'notes' => $this->text()->comment('заметки')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%task}}');
    }
}
