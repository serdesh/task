<?php

use yii\db\Migration;

/**
 * Handles adding done_date to table `{{%task}}`.
 */
class m190410_102724_add_done_date_column_to_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
            $this->addColumn(
                'task',
                'done_date',
                $this->timestamp()->defaultValue(null)->comment('Дата завершения задачи'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('task', 'done_date');
    }
}
