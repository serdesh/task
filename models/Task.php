<?php

namespace app\models;

use app\models\query\TaskQuery;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $description
 * @property string $start Начало выполнения (текущий период)
 * @property string $all_time Общее время выполнения
 * @property int $status Завершено = 1/В работе = 0
 * @property string $notes заметки
 * @property int $project_id ID проекта
 * @property string $done_date Дата завершения задачи
 * @property string $start_period Дата начала периода для отчета
 * @property string $end_period Дата завершения периода для отчета
 * @property string $json_text JSON текст
 * @property int $paid Флаг, оплачено (1) или нет (0)
 * @property array $projects Пректы
 * @property array $customers Заказчики
 * @property int $search_all Искать или нет в проектах-исключениях
 * @property double $agreed_price Согласованная сумма для оплата
 * @property int $plan_time Планируемое время
 * @property int $parent_task_id Родительская задача
 *
 * @property Project $project
 * @property Task $parentTask
 */
class Task extends ActiveRecord
{
    const TASK_STATUS_IN_WORK = 0;
    const TASK_STATUS_DONE = 1;

    public $start_period;
    public $end_period;
    public $search_all = 0;
    public $projects;
    public $json_text;
    public $customers;
    public $parented;

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
            [['start', 'all_time', 'projects', 'json_text', 'customers', 'parented'], 'safe'],
            [['status', 'project_id', 'search_all', 'paid', 'plan_time', 'parent_task_id'], 'integer'],
            [
                ['project_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Project::className(),
                'targetAttribute' => ['project_id' => 'id']
            ],
            [['start_period', 'end_period'], 'safe'],
            [['agreed_price'], 'number'],
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
            'all_time' => 'Общее время',
            'status' => 'Статус',
            'notes' => 'Примечание',
            'project_id' => 'Проект',
            'done_date' => 'Дата завершения',
            'start_period' => 'Начало периода',
            'end_period' => 'Конец периода',
            'projects' => 'Проект',
            'json_text' => 'JSON текст',
            'paid' => 'Оплачено',
            'agreed_price' => 'Фикс. сумма задачи',
            'plan_time' => 'Время план.',
            'parent_task_id' => 'Родительская задача',
        ];
    }

    public function beforeSave($insert)
    {
        if (!$this->done_date && $this->status == self::TASK_STATUS_DONE) {
            //Если нет даты завершения и статус "Завершено"
            $this->done_date = date('Y-m-d H:i:s', time());
        } elseif ($this->done_date && $this->status == self::TASK_STATUS_IN_WORK) {
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
     * @return ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getBoss()
    {
        return $this->hasOne(Boss::className(), ['id' => 'boss_id'])
            ->viaTable('project', ['id' => 'project_id']);
    }

    /**
     * {@inheritdoc}
     * @return TaskQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TaskQuery(get_called_class());
    }

    public static function getStatusName($status_value)
    {
        if ($status_value == self::TASK_STATUS_IN_WORK) {
            return 'В работе';
        }

        return 'Завершена';
    }

    /**
     * @return array
     */
    public static function getStatusList()
    {
        return ArrayHelper::map([
            ['id' => self::TASK_STATUS_IN_WORK, 'name' => 'В работе'],
            ['id' => self::TASK_STATUS_DONE, 'name' => 'Завершена'],
        ], 'id', 'name');
    }

    /**
     * @param string $date_1 Date Should In YYYY-MM-DD Format
     * @param string $date_2 Date Should In YYYY-MM-DD Format
     * RESULT FORMAT:
     * '%y Year %m Month %d Day %h Hours %i Minute %s Seconds'        =>  1 Year 3 Month 14 Day 11 Hours 49 Minute 36 Seconds
     * '%y Year %m Month %d Day'                                    =>  1 Year 3 Month 14 Days
     * '%m Month %d Day'                                            =>  3 Month 14 Day
     * '%d Day %h Hours'                                            =>  14 Day 11 Hours
     * '%d Day'                                                        =>  14 Days
     * '%h Hours %i Minute %s Seconds'                                =>  11 Hours 49 Minute 36 Seconds
     * '%i Minute %s Seconds'                                        =>  49 Minute 36 Seconds
     * '%h Hours                                                    =>  11 Hours
     * '%a Days                                                        =>  468 Days
     * @return string
     */
    public static function dateDifference($date_1, $date_2)
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);

        $interval = date_diff($datetime1, $datetime2);

        $minutes = 0;
        $hours = $interval->format('%h');


        if ($hours > 0) {
            $minutes = (int)$hours * 60;
        }

        $minutes += (int)$interval->format('%i');

        return $minutes;

    }

    /**
     * Считает кол-во времени, потраченного на завершенные задачи не моих проектов за текущий месяц
     * @return string
     */
    public static function getDoneTimePerMonth()
    {
        $all_min = self::find()
            ->joinWith(['project p'])
            ->andWhere(['task.status' => self::TASK_STATUS_DONE])
            ->andWhere(['BETWEEN', 'task.done_date', date('Y-m-01 00:00:00', time()), date('Y-m-d H:i:s', time())])
            ->andWhere(['p.exclude_statistic' => 0])
            ->sum('task.all_time');

        return self::formatMinutes($all_min);

    }

    public static function formatMinutes($all_min)
    {
        $hour = (int)($all_min / 60);

        if ($hour < 1) {
            $hour = 0;
        }

        $min = $all_min - ($hour * 60);

        if ($hour == 0) {
            return $min . ' мин.';
        } else {
            return $hour . ' ч. ' . $min . ' мин. (' . $all_min . ' мин.)';
        }

    }

    /**
     * Получает общее время задач
     * @param $all_project
     * @param array $projects
     * @return string
     */
    public function getAllDoneTime($all_project, $projects = [])
    {
        $query = Task::find()
            ->joinWith(['project p'])
            ->andWhere(['task.status' => 1])//Завершенная задача
            ->andWhere(['BETWEEN', 'task.done_date', $this->start_period, $this->end_period]);

        if (!$all_project) {
            //Если не учитывать проекты в исключениях
            $query->andWhere(['p.exclude_statistic' => 0]);
        }

        if ($projects) {
            $query->andWhere(['IN', 'p.id', $projects]);
        }
        $minutes = $query
            ->sum('all_time');

        return self::formatMinutes($minutes);

    }

    /**
     * Получает общее время выбранных задач
     * @param array $ids Идентификаторы задач
     * @return int
     */
    public function getAllTime($ids)
    {
        $sum = 0;

        foreach (self::find()->andWhere(['IN', 'id', $ids])->each() as $model) {
            $sum += (int)$model->all_time;
        }
        \Yii::info('Минут - ' . $sum, 'test');

        return $sum;
    }

    /**
     * Получает Идентификатор проекта последней добавленой задачи
     * @return int
     */
    public function getLastProjectId()
    {
        return Task::find()->orderBy(['id' => SORT_DESC])->one()->project_id;
    }

    public function getParentTask()
    {
        return $this->hasOne(Task::class, ['id' => 'parent_task_id']);
    }

    /**
     * Получает задачи для привязки.
     * Условия отбора:
     *  - Не оплачено
     *  - В работе
     *  - Тот же проект что и у текущей задачи
     *  - Нет родительской задачи
     *  - Не текущая задача (исключаем привязку задачи самой к себе)
     *
     * @return array
     */
    public function getTasks()
    {
        $query = Task::find()
            ->andWhere([
                'paid' => 0,
                'status' => self::TASK_STATUS_IN_WORK,
                'project_id' => $this->project_id,
                'parent_task_id' => null,
            ])
            ->andWhere(['<>', 'id', $this->id]);

        return ArrayHelper::map($query->all(), 'id', 'description');

    }
}
