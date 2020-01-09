<?php

use yii\db\Migration;

/**
 * Class m190408_103216_add_index_and_relations_to_tables
 */
class m190408_103216_add_index_and_relations_to_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx-task-project_id', 'task', 'project_id');
        $this->createIndex('idx-project-boss_id', 'project', 'boss_id');
        $this->createIndex('idx-boss-messenger_id', 'boss', 'messenger_id');

        $this->addForeignKey(
            'fk-task-project_id',
            'task',
            'project_id',
            'project',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-project-boss_id',
            'project',
            'boss_id',
            'boss',
            'id',
            'SET NULL'
        );
        $this->addForeignKey(
            'fk-boss-messenger_id',
            'boss',
            'messenger_id',
            'messenger',
            'id',
            'SET NULL'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190408_103216_add_index_and_relations_to_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190408_103216_add_index_and_relations_to_tables cannot be reverted.\n";

        return false;
    }
    */
}
