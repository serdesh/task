<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Projects';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="project-index">
    <div id="ajaxCrudDatatable">
        <?php try {
            echo GridView::widget([
                'rowOptions' => function ($model, $key, $index, $grid) {
                    if ($model->exclude_statistic) return ['class' => 'warning'];
                    return null;
                },
                'id' => 'crud-datatable',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'pjax' => true,
                'columns' => require(__DIR__ . '/_columns.php'),
                'toolbar' => [
                    ['content' =>
                        Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                            ['role' => 'modal-remote', 'title' => 'Create new Projects', 'class' => 'btn btn-default']) .
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
                    'heading' => '<i class="glyphicon glyphicon-list"></i> Projects listing',
                    'before' => '<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>',
                    'after' =>
//                        BulkButtonWidget::widget([
//                            'buttons' => Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; Delete All',
//                                ["bulkdelete"],
//                                [
//                                    "class" => "btn btn-danger btn-xs",
//                                    'role' => 'modal-remote-bulk',
//                                    'data-confirm' => false, 'data-method' => false,// for overide yii data api
//                                    'data-request-method' => 'post',
//                                    'data-confirm-title' => 'Are you sure?',
//                                    'data-confirm-message' => 'Are you sure want to delete this item'
//                                ]),
//                        ]) .
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
