<?php

namespace app\models;

use app\models\query\TaskQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $description
 * @property string $start Начало выполнения (текущий период)
 * @property string $all_time Общее время выполнения
 * @property int $status Завершено/В работе
 * @property string $notes заметки
 * @property int $project_id ID проекта
 *
 * @property Project $project
 */
class Task extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'notes'], 'string'],
            [['start', 'all_time'], 'safe'],
            [['status', 'project_id'], 'integer'],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Description',
            'start' => 'Start',
            'all_time' => 'All Time',
            'status' => 'Status',
            'notes' => 'Notes',
            'project_id' => 'Project ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }

    /**
     * {@inheritdoc}
     * @return TaskQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TaskQuery(get_called_class());
    }
}
