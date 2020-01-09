<?php

namespace app\models;

use app\models\query\ProjectQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "project".
 *
 * @property int $id
 * @property string $name
 * @property string $url
 * @property string $local_url
 * @property string $login
 * @property string $password
 * @property string $created_at
 * @property int $boss_id Владелец проекта
 * @property int $exclude_statistic Исключать из статистики
 *
 * @property Boss $boss
 * @property Task[] $tasks
 */
class Project extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url'], 'string'],
            [['created_at', 'local_url'], 'safe'],
            [['boss_id', 'exclude_statistic'], 'integer'],
            [['name', 'login', 'password'], 'string', 'max' => 255],
            [['boss_id'], 'exist', 'skipOnError' => true, 'targetClass' => Boss::className(), 'targetAttribute' => ['boss_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'url' => 'Url',
            'local_url' => 'Локальный Url',
            'login' => 'Логин',
            'password' => 'Пароль',
            'created_at' => 'Создан',
            'boss_id' => 'ID босса',
            'exclude_statistic' => 'Исключать из статистики',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBoss()
    {
        return $this->hasOne(Boss::className(), ['id' => 'boss_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['project_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ProjectQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProjectQuery(get_called_class());
    }

    /**
     * Get List of projects
     * @return array
     */
    public static function getProjectList()
    {
        $projects = self::find()->all();

        return ArrayHelper::map($projects, 'id', 'name');
    }
}
