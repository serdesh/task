<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\TaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Задачи';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
    <div class="task-index">
        <div id="ajaxCrudDatatable">
            <?php try {
                echo GridView::widget([
                    'id' => 'crud-datatable',
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'pjax' => true,
                    'columns' => require(__DIR__ . '/_columns.php'),
                    'toolbar' => [
                        ['content' =>
                            Html::a('<i class="glyphicon glyphicon-plus"></i>', ['add-empty-task'],
                                ['role' => 'modal-remote', 'title' => 'Create new Tasks', 'class' => 'btn btn-default']) .
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
//                    'before' => '<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>',
                        'after' => BulkButtonWidget::widget([
                                'buttons' => Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; Удалить выделенное',
                                    ["bulkdelete"],
                                    [
                                        "class" => "btn btn-danger btn-xs",
                                        'role' => 'modal-remote-bulk',
                                        'data-confirm' => false, 'data-method' => false,// for overide yii data api
                                        'data-request-method' => 'post',
                                        'data-confirm-title' => 'Are you sure?',
                                        'data-confirm-message' => 'Are you sure want to delete this item'
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
            var url =  'task/set-status';
            var id = select.attr('data-id');
            $.post(
                url,
                {
                    id: id,
                    status: selected_value
                },
                function(response) {
                  console.log(response);
                  if ((response == 'success') && (selected_value == '1')){
                      //if status DONE
                      select.hide();
                      var td = select.parent('td');
                      td.append('Завершена');
                  } 
                }
            )
        });
    })
JS;

$this->registerJs($script);
?>