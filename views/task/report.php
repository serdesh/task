<?php

use app\models\Boss;
use app\models\Project;
use app\models\Task;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use kartik\switchinput\SwitchInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\TaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $full_time string */
/* @var $model Task */

$this->title = 'Задачи';
$this->params['breadcrumbs'][] = $this->title;
$post = Yii::$app->request->post();

CrudAsset::register($this);

$dataProvider->pagination->pageSize = 40;

if (!$model->start_period) {
    $model->start_period = '2010-01-01 00:00:00';
} elseif (!strpos($model->start_period, '00:00:00')){
    $model->start_period = $model->start_period . ' 00:00:00';
}
if (!$model->end_period) {
    $model->end_period = date('Y-m-d 23:59:59', time());
} elseif (!strpos($model->end_period, '23:59:59')){
    $model->end_period = $model->end_period . ' 23:59:59';
}



$before_text = '<em>Время завершенных задач за период: ' . $full_time . '</em>';


?>
    <div class="task-report-form">
        <?php $form = ActiveForm::begin(); ?>
        <div class="row filter-report">
                <div class="col-md-3">
                <?= $form->field($model, 'start_period')->widget(DatePicker::class, [
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'options' => [
                        'autocomplete' => 'off',
                    ],
                    'pluginOptions' => [
                        'startView' => 'months',
                        'format' => 'yyyy-mm-dd',
//                        'format' => 'dd.mm.yyyy',
                        'allowClear' => true,
                        'closeOnSelect' => true,
                    ]
                ]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'end_period')->widget(DatePicker::class, [
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'options' => [
                        'autocomplete' => 'off',
                    ],
                    'pluginOptions' => [
                        'todayHighlight' => true,
                        'todayBtn' => true,
                        'startView' => 'months',
                        'format' => 'yyyy-mm-dd',
                        'allowClear' => true,
                        'closeOnSelect' => true,
                    ]
                ]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'projects')->widget(Select2::class, [
                    'language' => 'ru',
//                    'value' => $post['projects'],
                    'data' => Arrayhelper::map(Project::find()->all(), 'id', 'name'),
                    'size' => Select2::MEDIUM,
                    'options' => ['placeholder' => 'Выберите один или несколько проектов', 'multiple' => true],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]) ?>
            </div>

        </div>
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'search_all')->widget(SwitchInput::class, [
                    'pluginOptions' => [
//                        'size' => 'large',
                        'onColor' => 'success',
//                        'offColor' => 'warning',
                        'onText' => 'Поиск по всем проектам',
                        'offText' => 'Не искать в исключениях'
                    ]
                ])->label(false) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'paid')->widget(SwitchInput::class, [
                    'pluginOptions' => [
                        'onColor' => 'success',
//                        'offColor' => 'error',
                        'onText' => 'Все задачи',
                        'offText' => 'Не показывать оплаченные'
                    ]
                ])->label(false) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'customers')->widget(Select2::class, [
                    'language' => 'ru',
//                    'value' => $post['projects'],
                    'data' => Boss::getList(),
                    'size' => Select2::MEDIUM,
                    'options' => ['placeholder' => 'Заказчики', 'multiple' => true],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label(false) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= Html::submitButton('Показать',
                    [
                            'class' => 'btn btn-success btn-block',
                        'style' => 'margin-bottom: 30px;'
                    ]) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

    <div class="task-report">
        <div id="ajaxCrudDatatable">
            <?php try {
                echo GridView::widget([
                    'id' => 'crud-datatable',
                    'dataProvider' => $dataProvider,
//                    'filterModel' => $searchModel,
                    'pjax' => true,
                    'columns' => require(__DIR__ . '/_report_columns.php'),
                    'toolbar' => [
                        [
                            'content' =>
                                Html::a('<i class="glyphicon glyphicon-plus"></i>', ['add-empty-task'],
                                    [
                                        'role' => 'modal-remote',
                                        'title' => 'Create new Tasks',
                                        'class' => 'btn btn-default'
                                    ]) .
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
                        'before' => $before_text,
                        'after' =>
//                            BulkButtonWidget::widget([
//                                'buttons' => Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; Удалить выделенное',
//                                    ["bulkdelete"],
//                                    [
//                                        "class" => "btn btn-danger btn-xs",
//                                        'role' => 'modal-remote-bulk',
//                                        'data-confirm' => false, 'data-method' => false,// for overide yii data api
//                                        'data-request-method' => 'post',
//                                        'data-confirm-title' => 'Are you sure?',
//                                        'data-confirm-message' => 'Are you sure want to delete this item'
//                                    ]),
//                            ]) .
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
//$script = <<<JS
//    $( document ).ready(function() {
//        $(document).on('change', '#statuses-dropdown', function(event){
//            var select = $(event.target);
//            var selected_value = select.val();
//            var url =  '/task/set-status';
//            var id = select.attr('data-id');
//            $.post(
//                url,
//                {
//                    id: id,
//                    status: selected_value
//                },
//                function(response) {
//                  console.log(response);
//                  if ((response == 'success') && (selected_value == '1')){
//                      //if status DONE
//                      select.hide();
//                      var td = select.parent('td');
//                      td.append('Завершена');
//                      var start_btn = $('#start-btn-' + id);
//                      start_btn.css('display', 'none');
//
//                  }
//                }
//            )
//        });
//        $(document).on('change', '#project-dropdown', function(event){
//            var select = $(event.target);
//            var selected_value = select.val();
//            var url =  '/task/set-project';
//            var id = select.attr('data-id');
//            $.post(
//                url,
//                {
//                    id: id,
//                    project_id: selected_value
//                },
//                function(response) {
//                  console.log(response);
//                  if (response[0] == 'success'){
//                      //if status DONE
//                      select.hide();
//                      var td = select.parent('td');
//                      td.append(response[1]);
//                  }
//                }
//            )
//        });
//    })
//JS;
//
//$this->registerJs($script);
?>