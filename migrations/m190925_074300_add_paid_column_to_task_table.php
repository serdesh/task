<?php

use yii\db\Migration;

/**
 * Handles adding paid to table `{{%task}}`.
 */
class m190925_074300_add_paid_column_to_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('task', 'paid', $this->smallInteger(1)->defaultValue(0)->comment('Оплачено'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('task', 'paid');
    }
}
