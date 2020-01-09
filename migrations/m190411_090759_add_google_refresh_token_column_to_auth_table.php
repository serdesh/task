<?php

use yii\db\Migration;

/**
 * Handles adding google_refresh_token to table `{{%auth}}`.
 */
class m190411_090759_add_google_refresh_token_column_to_auth_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('auth', 'google_refresh_token', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
