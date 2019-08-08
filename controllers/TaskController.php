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
                'id'
            ],
            'defaultOrder' => [
                'start' => SORT_DESC,
                'status' => SORT_ASC,
                'id' => SORT_DESC,
            ]
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
                    'title' => "Create new Task",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close',
                            ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else {
                if ($model->load($request->post()) && $model->save()) {
                    return $this->redirect('/task/index');

//                return [
//                    'forceReload' => '#crud-datatable-pjax',
//                    'title' => "Create new Task",
//                    'content' => '<span class="text-success">Create Task success</span>',
//                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
//                        Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
//
//                ];
                } else {
                    return [
                        'title' => "Create new Task",
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Close',
                                ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])

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
                    return $this->redirect('/task/index');
//                return [
//                    'forceReload' => '#crud-datatable-pjax',
//                    'forceClose' => true,
//                    'title' => "Task #" . $id,
//                    'content' => $this->renderAjax('view', [
//                        'model' => $model,
//                    ]),
//                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
//                        Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
//                ];
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
            throw new NotFoundHttpException('The requested page does not exist.');
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
        if (!$model->save()) {
            Yii::error($model->errors, __METHOD__);
            Yii::$app->session->setFlash('error', 'Ошибка добавления');
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
//        return $this->redirect(['index']);
    }

    /**
     * @param int $id ID задачи
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionStartTask($id)
    {
        $model = $this->findModel($id);

        if ($model->start) {
            //Если есть начало периода - к общему времени добавляем прошедшее время между началом и текущим моментом
            $date_diff = Task::dateDifference(date('Y-m-d H:i', time()), $model->start); //Разница в минутах

            Yii::info('Date-diff(min): ' . $date_diff, 'test');

            $all_time = $model->all_time;

            if ($date_diff) {

                //Добавляем к общему увремени
                $all_time += (int)$date_diff;
                $model->all_time = $all_time;

                Yii::info('All time(min): ' . $date_diff, 'test');
            }

            if (!$model->save()) {
                Yii::error($model->errors, __METHOD__);
                Yii::$app->session->setFlash('error', 'Ошибка сохранения общего времени');
            } else {
                $model->start = null;
                if (!$model->save()) {
                    Yii::error($model->errors, __METHOD__);
                    Yii::$app->session->setFlash('error', 'Ошибка обнуления старт/стоп');
                }
            }
        } else {
            $model->start = date('Y-m-d H:i', time());
            if (!$model->save()) {
                Yii::error($model->errors, __METHOD__);
                Yii::$app->session->setFlash('error', 'Ошибка сохранения старт/стоп');
            }
        }
        return $this->redirect('/task/index');
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
                ->andWhere(['task.status' => 1])//Завершенная задача
        ]);

        if ($request->isPost) {
            if ($model->load($request->post())) {
                $dataProvider->query
                    ->andWhere(['BETWEEN', 'task.done_date', $model->start_period, $model->end_period]);
                if (!$model->search_all) {
                    $dataProvider->query
                        ->andWhere(['p.exclude_statistic' => 0]); //Не исключенные из статистики
                }
                if (count($model->projects) > 0 && $model->projects != '') {
                    $dataProvider->query
                        ->andWhere(['IN', 'p.id', $model->projects]);
                }
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка загрузки данных модели');
                return $this->redirect('report?search_all=' . $model->search_all);
            }


        }
        $dataProvider->setSort([
            'defaultOrder' => [
                'done_date' => SORT_DESC,
            ]
        ]);
        return $this->render('report', [
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    public function actionDecoder()
    {
        $request = Yii::$app->request;
        $model = new Task();

        if ($request->isPost){
            $model->load($request->post());
        }
        return $this->render('decoder',[
            'model' => $model,
        ]);
    }

}
