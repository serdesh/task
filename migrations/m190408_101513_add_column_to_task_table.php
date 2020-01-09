<?php

use yii\db\Migration;

/**
 * Class m190408_101513_add_column_to_task_table
 */
class m190408_101513_add_column_to_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%task}}', 'project_id', $this->integer()->comment('ID проекта'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190408_101513_add_column_to_task_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190408_101513_add_column_to_task_table cannot be reverted.\n";

        return false;
    }
    */
}
