<?php

use yii\db\Migration;

/**
 * Class m190408_123417_change_all_time_column_to_task_table
 */
class m190408_123417_change_all_time_column_to_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('task', 'all_time', $this->integer()->comment('Общее время затраченное на задачу, в минутах')->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190408_123417_change_all_time_column_to_task_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190408_123417_change_all_time_column_to_task_table cannot be reverted.\n";

        return false;
    }
    */
}
