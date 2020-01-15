<?php

namespace app\controllers;

use app\models\Project;
use Yii;
use app\models\Task;
use app\models\search\TaskSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class TaskController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulkdelete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Task models.
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;

        //if Editable
        if ($request->isPost && $request->post('hasEditable')) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $index = $request->post('editableIndex'); //Порядковый номер редактируемой ячейки (считается с нуля)
            $key = $request->post('editableKey'); //Id редактируемой задачи
            $attribute = $request->post('editableAttribute'); //Наименование редактируемого атрибута

            $model = $this->findModel($key);

            Yii::info($model->$attribute, 'test');

            $model->$attribute = $request->post('Task')[$index][$attribute];
            $model->save();
            Yii::info($model->$attribute, 'test');

            if ($model->hasErrors()) {
                Yii::error($model->errors, __METHOD__);
                Yii::$app->session->setFlash(implode(PHP_EOL, $model->errors));
            }
            return ['output' => $model->$attribute];
        }

        $searchModel = new TaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort([
            'attributes' => [
                'start',
                'status',
                'done_date',
//                'id'
            ],
            'defaultOrder' => [
                'start' => SORT_DESC,
                'status' => SORT_ASC,
                'done_date' => SORT_DESC,
//                'id' => SORT_DESC,
            ]
        ]);
        $dataProvider->pagination = false;

        $task_complete = Task::getDoneTimePerMonth();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'task_complete' => $task_complete,
        ]);
    }

    /**
     * Displays a single Task model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Task #" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Task model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Task();

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Создание задачи",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Закрыть',
                            ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Сохранить', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else {
                if ($model->load($request->post()) && $model->save()) {
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'forceClose' => 'true',
                    ];
                } else {
                    return [
                        'title' => "Создание задачи",
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Закрыть',
                                ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Сохранить', ['class' => 'btn btn-primary', 'type' => "submit"])

                    ];
                }
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }

    }

    /**
     * Updates an existing Task model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Редактирование задачи #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Закрыть',
                            ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Сохранить', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else {
                if ($model->load($request->post()) && $model->save()) {
//                    return $this->redirect('/task/index');
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'forceClose' => true,
                    ];
                } else {
                    return [
                        'title' => "Редактирование задачи #" . $id,
                        'content' => $this->renderAjax('update', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Закрыть',
                                ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Сохранить', ['class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                }
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Task model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }


    }

    /**
     * Delete multiple existing Task model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionBulkdelete()
    {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }

    }

    /**
     * Выставляет флаг Оплачено, отмеченным задачам
     * @return array|Response
     * @throws NotFoundHttpException
     */
    public function actionBulkPaid()
    {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys

        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            $model->paid = 1;

            if (!$model->save()) {
                Yii::error($model->errors, '_error');
            }
        }

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая страница не существует.');
        }
    }

    /**
     * Add Task
     * @return array
     */
    public function actionAddEmptyTask()
    {
        $model = new Task();
        $model->status = Task::TASK_STATUS_IN_WORK;
        $model->project_id = $model->getLastProjectId();

        Yii::info($model->attributes, 'test');
        if (!$model->save()) {
            Yii::error($model->errors, __METHOD__);
            Yii::$app->session->setFlash('error', 'Ошибка добавления');
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
//        return $this->redirect(['index']);
    }

    /**
     * Отмечает время начала выполнения задачи
     * @param int $id ID задачи
     * @return array При удачном выполнении возвращает ['success' => 1], при ошибке ['success' => 0, 'data' => 'Текст ошибки']
     * @throws NotFoundHttpException
     */
    public function actionStartTask($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($id);

        if ($model->start) {
            //Если есть начало периода - к общему времени добавляем прошедшее время между началом и текущим моментом
            $date_diff = Task::dateDifference(date('Y-m-d H:i', time()), $model->start); //Разница в минутах

            Yii::info('Date-diff(min): ' . $date_diff, 'test');

            $all_time = $model->all_time;

            if (isset($date_diff)) {

                //Добавляем к общему времени
                $all_time += (int)$date_diff;
                $model->all_time = $all_time;

                Yii::info('All time(min): ' . $date_diff, 'test');
            }

            if (!$model->save()) {
                Yii::error($model->errors, 'error');
                return ['success' => 0, 'data' => 'Ошибка сохранения общего времени'];
            } else {
                $model->start = null;
                if (!$model->save()) {
                    Yii::error($model->errors, __METHOD__);
                    return ['success' => 0, 'data' => 'Ошибка обнуления старт/стоп'];
                }
            }
            $status = 'stopped';
        } else {
            $model->start = date('Y-m-d H:i', time());
            if (!$model->save()) {
                Yii::error($model->errors, __METHOD__);
                return ['success' => 0, 'data' => 'Ошибка сохранения старт/стоп'];
            }
            $status = 'started';
        }

        $time = Task::formatMinutes($model->all_time);
        return ['success' => 1, 'time' => $time, 'status' => $status];
    }

    /**
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionSetStatus()
    {
        $request = Yii::$app->request;

        $id = $request->post('id');
        $status = $request->post('status');

        $model = $this->findModel($id);

        Yii::info('Status after: ' . $model->status, 'test');

        $model->status = $status;

        Yii::info('Status before: ' . $model->status, 'test');

        if (!$model->save()) {
            Yii::error($model->errors, __METHOD__);
            return 'error';
        }

        return 'success';
    }

    /**
     * Set project for Task
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionSetProject()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;

        $id = $request->post('id');
        $project_id = $request->post('project_id');

        $model = $this->findModel($id);

        $project_name = Project::findOne($project_id)->name;

        $model->project_id = $project_id;

        if (!$model->save()) {
            Yii::error($model->errors, __METHOD__);
            return ['error'];
        }

        return ['success', $project_name];
    }

    public function actionReport()
    {
        $request = Yii::$app->request;
        $model = new Task();

        $dataProvider = new ActiveDataProvider([
            'query' => Task::find()
                ->joinWith(['project p'])
                ->joinWith(['boss'])
                ->andWhere(['task.status' => 1])//Завершенная задача
        ]);

        if ($request->isPost) {
            if ($model->load($request->post())) {
                $start_period = $model->start_period . ' 00:00:00';

                if (!$model->end_period) {
                    $end_period = date('Y-m-d 23:59:59', time());
                } else {
                    $end_period = $model->end_period . ' 23:59:59';
                }
                $dataProvider->query
                    ->andWhere(['BETWEEN', 'task.done_date', $start_period, $end_period]);
                if (!$model->search_all) {
                    $dataProvider->query
                        ->andWhere(['p.exclude_statistic' => 0]); //Не исключенные из статистики
                }
                if ($model->projects) {
                    if (count($model->projects) > 0 && $model->projects != '') {
                        $dataProvider->query
                            ->andWhere(['IN', 'p.id', $model->projects]);
                    }
                }

                if ($model->customers) {
                    if (count($model->customers) > 0 && $model->customers != '') {
                        $dataProvider->query
                            ->andWhere(['IN', 'boss.id', $model->customers]);
                    }
                }

                if ($model->paid == 0) {
                    $dataProvider->query
                        ->andWhere(['paid' => 0]); //По умолчанию отображаем только не оплаченные
                }
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка загрузки данных модели');
                return $this->redirect('report?search_all=' . $model->search_all);
            }
        } else {
            $model->start_period = date('Y-m-01', time());
            $model->end_period = date('Y-m-d', time());
            $dataProvider->query
                ->andWhere(['BETWEEN', 'done_date', $model->start_period, $model->end_period])
                ->andWhere(['paid' => 0])
                ->andWhere(['p.exclude_statistic' => 0]);
        }

        $dataProvider->setSort([
            'defaultOrder' => [
                'done_date' => SORT_DESC,
            ]
        ]);

        $dataProvider->pagination = false;

        return $this->render('report', [
            'dataProvider' => $dataProvider,
            'model' => $model,
            'full_time' => Task::formatMinutes($model->getAllTime($dataProvider->getKeys())),
        ]);
    }

    public function actionDecoder()
    {
        $request = Yii::$app->request;
        $model = new Task();

        if ($request->isPost) {
            $model->load($request->post());
        }
        return $this->render('decoder', [
            'model' => $model,
        ]);
    }

}
