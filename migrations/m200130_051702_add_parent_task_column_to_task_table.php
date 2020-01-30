<?php

use yii\db\Migration;

/**
 * Handles adding parent_task to table `{{%task}}`.
 */
class m200130_051702_add_parent_task_column_to_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('task', 'parent_task_id', $this->integer()->comment('Задача - родитель'));

        $this->addForeignKey(
            'fk-task-parent_task_id',
            'task',
            'parent_task_id',
            'task',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-task-parent_task_id', 'task');
        $this->dropColumn('task', 'parent_task_id');
    }
}
