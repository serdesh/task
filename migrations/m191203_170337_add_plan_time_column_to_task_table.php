<?php

use yii\db\Migration;

/**
 * Handles adding plan_time to table `{{%task}}`.
 */
class m191203_170337_add_plan_time_column_to_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('task', 'plan_time', $this->integer()->comment('Планируемое время, мин.'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('task', 'plan_time');
    }
}
