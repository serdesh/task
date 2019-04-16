<?php

use yii\db\Migration;

/**
 * Handles adding local_url to table `{{%project}}`.
 */
class m190415_171556_add_local_url_column_to_project_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('project', 'local_url', $this->text()->comment('Локальный URL'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
