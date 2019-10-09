<?php

use app\models\Task;
use johnitvn\ajaxcrud\BulkButtonWidget;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\TaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Задачи';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

$dataProvider->pagination->pageSize = 40;

?>
    <div class="task-index">
        <div id="ajaxCrudDatatable">
            <?php try {
                echo GridView::widget([
                    'id' => 'crud-datatable',
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'pjax' => true,
                    'rowOptions' => function ($model) {
                        if (isset($model->project->exclude_statistic) && $model->paid == 0){
                            if ($model->project->exclude_statistic){
                                return ['class' => 'warning'];
                            }
                        } elseif ($model->paid == 1){
                            return ['class' => 'success'];
                        }
                        return null;
                    },
                    'columns' => require(__DIR__ . '/_columns.php'),
                    'toolbar' => [
                        ['content' =>
                            Html::a('<i class="glyphicon glyphicon-plus"></i>', ['add-empty-task'],
                                ['role' => 'modal-remote', 'title' => 'Добавить задачу', 'class' => 'btn btn-default']) .
                            Html::a('Показать всё', ['index', 'TaskSearch[paid]' => [0, 1]],
                                ['title' => 'Отобразить все задачи', 'class' => 'btn btn-default']) .
                            Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                                ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Reset Grid']) .
                            '{toggleData}' .
                            '{export}'
                        ],
                    ],
                    'striped' => true,
                    'condensed' => true,
                    'responsive' => true,
                    'panel' => [
                        'type' => 'primary',
                        'heading' => '<i class="glyphicon glyphicon-list"></i> Список задач',
                        'before' => '<em>Время завершенных задач за текущий месяц: ' . Task::getDoneTimePerMonth() . '</em>',
                        'after' =>
                            BulkButtonWidget::widget([
                                'buttons' => Html::a('<i class="glyphicon glyphicon-ruble"></i>&nbsp; Оплачено',
                                    ["bulk-paid"],
                                    [
                                        "class" => "btn btn-primary btn-xs",
                                        'role' => 'modal-remote-bulk',
                                        'data-confirm' => false, 'data-method' => false,// for overide yii data api
                                        'data-request-method' => 'post',
                                        'data-confirm-title' => 'Уверен?',
                                        'data-confirm-message' => 'Действительно отметить как "Оплачено"?'
                                    ]),
                            ]) .
                            '<div class="clearfix"></div>',
                    ]
                ]);
            } catch (Exception $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
                Yii::error($e->getTraceAsString(), __METHOD__);
            } ?>
        </div>
    </div>
<?php Modal::begin([
    "id" => "ajaxCrudModal",
    "footer" => "",// always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>

<?php
$script = <<<JS
    $( document ).ready(function() {
        $(document).on('change', '#statuses-dropdown', function(event){
            var select = $(event.target);
            var selected_value = select.val();
            var url =  '/task/set-status';
            var id = select.attr('data-id');
            $.post(
                url,
                {
                    id: id,
                    status: selected_value
                },
                function(response) {
                  console.log(response);
                  if ((response === 'success') && (selected_value === '1')){
                      //if status DONE
                      select.hide();
                      var td = select.parent('td');
                      td.append('Завершена');
                      var start_btn = $('#start-btn-' + id);
                      start_btn.css('display', 'none');
                      
                  } 
                }
            )
        });
        $(document).on('change', '#project-dropdown', function(event){
            var select = $(event.target);
            var selected_value = select.val();
            var url =  '/task/set-project';
            var id = select.attr('data-id');
            $.post(
                url,
                {
                    id: id,
                    project_id: selected_value
                },
                function(response) {
                  console.log(response);
                  if (response[0] === 'success'){
                      //if status DONE
                      select.hide();
                      var td = select.parent('td');
                      td.append(response[1]);
                  } 
                }
            )
        });
        $(document).on('click', '.start-btn', function(e) {
            e.preventDefault();
            var btn = $(this);
            var task_id = btn.attr('data-id');
            $.get(
                '/task/start-task',
                {
                    id: task_id
                },
                function(response) {
                    console.log(response);
                    if (response['success'] === 1){
                        if (btn.html() === 'Стоп'){
                            btn.html('Старт');
                            btn.removeClass('btn-danger');
                            btn.addClass('btn-success');
                            $('#time-' + task_id).html(response['time']);
                        } else {
                            btn.html('Стоп');
                            btn.removeClass('btn-success');
                            btn.addClass('btn-danger');
                        }
                    } else {
                        //Выводим текст ошибки
                         alert(response['data']);
                    }
                }
            )
        })
    })
JS;

$this->registerJs($script);
?>