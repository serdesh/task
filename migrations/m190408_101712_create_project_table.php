<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%project}}`.
 */
class m190408_101712_create_project_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%project}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'url' => $this->text(),
            'login' => $this->string(),
            'password' => $this->string(),
            'created_at' => $this->timestamp()->defaultValue(new \yii\db\Expression('NOW()')),
            'boss_id' => $this->integer()->comment('Владелец проекта'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%project}}');
    }
}
