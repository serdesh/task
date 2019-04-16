<?php

use yii\db\Migration;

/**
 * Handles adding exclude_statistic to table `{{%project}}`.
 */
class m190416_082453_add_exclude_statistic_column_to_project_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'project',
            'exclude_statistic',
            $this->smallInteger(1)
                ->defaultValue(0)
                ->comment('Исключать из статистики'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('project','exclude_statistic');
    }
}
