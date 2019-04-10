<?php

namespace app\models;

use app\models\query\TaskQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

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
 * @property string $done_date Дата завершения задачи
 *
 * @property Project $project
 */
class Task extends ActiveRecord
{
    const TASK_STATUS_IN_WORK = 0;
    const TASK_STATUS_DONE = 1;

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
            'description' => 'Описание',
            'start' => 'Начало',
            'all_time' => 'Общее время (мин.)',
            'status' => 'Статус',
            'notes' => 'Примечание',
            'project_id' => 'Проект',
            'done_date' => 'Дата завершения',
        ];
    }

    public function beforeSave($insert)
    {
        if (!$this->done_date && $this->status == self::TASK_STATUS_DONE){
            //Если нет даты завершения и статус "Завершено"
            $this->done_date = date('Y-m-d H:i:s', time());
        } elseif ($this->done_date && $this->status == self::TASK_STATUS_IN_WORK){
            //Если дата завершения есть и статус задачи "Выполняется"
            $this->done_date = null;
        }

        return parent::beforeSave($insert);
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

    public static function getStatusName($status_value){
        if ($status_value == self::TASK_STATUS_IN_WORK){
            return 'В работе';
        }

        return 'Завершена';
    }

    /**
     * @return array
     */
    public static function getStatusList() {
        return ArrayHelper::map([
           ['id' => self::TASK_STATUS_IN_WORK, 'name' => 'В работе'],
           ['id' => self::TASK_STATUS_DONE, 'name' => 'Завершена'],
        ], 'id', 'name');
    }

    /**
     * @param string $date_1 Date Should In YYYY-MM-DD Format
     * @param string $date_2 Date Should In YYYY-MM-DD Format
     * RESULT FORMAT:
     '%y Year %m Month %d Day %h Hours %i Minute %s Seconds'        =>  1 Year 3 Month 14 Day 11 Hours 49 Minute 36 Seconds
     '%y Year %m Month %d Day'                                    =>  1 Year 3 Month 14 Days
     '%m Month %d Day'                                            =>  3 Month 14 Day
     '%d Day %h Hours'                                            =>  14 Day 11 Hours
     '%d Day'                                                        =>  14 Days
     '%h Hours %i Minute %s Seconds'                                =>  11 Hours 49 Minute 36 Seconds
     '%i Minute %s Seconds'                                        =>  49 Minute 36 Seconds
     '%h Hours                                                    =>  11 Hours
     '%a Days                                                        =>  468 Days
     * @return string
     */
    public static function dateDifference($date_1 , $date_2 )
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);

        $interval = date_diff($datetime1, $datetime2);

        $minutes = 0;
        $hours = $interval->format('%h');


        if ($hours > 0) {
            $minutes = (int)$hours * 60;
        }

        $minutes +=  (int)$interval->format('%i');

        return $minutes;

    }

    public static function getDoneTimePerMonth()
    {
        $all_min = self::find()
            ->where(['status' => self::TASK_STATUS_DONE])
//            ->andWhere(['BETWEEN', 'done_date' , date('Y-m-01 00:00:00', time()), date('Y-m-d H:i:s', time())])
            ->sum('all_time');

        $hour = (int)($all_min / 60);

        if ($hour < 1) $hour = 0;

        $min = $all_min - ($hour * 60);

        return $hour . 'ч. ' . $min . 'мин.';
    }
}
