<?php

use yii\db\Migration;

/**
 * Handles adding paid_date to table `{{%task}}`.
 */
class m210114_090223_add_paid_date_column_to_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%task}}', 'paid_date', $this->date()->comment('Дата оплаты'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%task}}', 'paid_date');
    }
}
