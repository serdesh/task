<?php

use yii\db\Migration;

/**
 * Handles adding sum to table `{{%task}}`.
 */
class m191203_113639_add_agreed_price_column_to_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('task', 'agreed_price',
            $this->double(2)->defaultValue(0)->comment('Согласованная сумма оплаты'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('task', 'agreed_price');
    }
}
