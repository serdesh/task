<?php

use app\models\Project;
use app\models\Task;
use yii\bootstrap\Html;
use yii\helpers\Url;

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
//    [
//        'class' => 'kartik\grid\SerialColumn',
//        'width' => '30px',
//    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'id',
        'label' => '#',
        'filter' => false,
    ],
    [
        'class' => '\kartik\grid\EditableColumn',
        'attribute' => 'description',
        'editableOptions' => [
            'asPopover' => false,
        ],
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'project_id',
        'value' => function ($data) {
            if (!$data->project_id) {
                return Html::activeDropDownList($data, 'project_id', Project::getProjectList(), [
                    'id' => 'project-dropdown',
                    'data-id' => $data->id,
                    'style' => 'width: 100%; border-radius: 4px; padding: 5px;',
                    'prompt' => 'Выберите проект',
                ]);
            }
            Yii::setAlias('@example', 'http://' . $data->project->name . '/');
            return Html::a($data->project->name, '@example', ['target' => '_blank']);
        },
        'format' => 'raw',

    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'all_time',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'status',
        'value' => function ($data) {
            if (!$data->status) {
                return Html::activeDropDownList($data, 'status', Task::getStatusList(), [
                    'id' => 'statuses-dropdown',
                    'data-id' => $data->id,
                    'style' => 'width: 100%; border-radius: 4px; padding: 5px;'
                ]);
            }
            return Task::getStatusName($data->status);
        },
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'value' => function ($data) {
            if (!$data->status) {
                if ($data->start) {
                    return Html::a('Стоп', ['start-task', 'id' => $data->id], [
                        'class' => 'btn btn-danger',
                    ]);
                }
                return Html::a('Старт', ['start-task', 'id' => $data->id], [
                    'class' => 'btn btn-success',

                ]);
            }
            return '';
        },
        'format' => 'raw',

    ],
//    [
//        'class'=>'\kartik\grid\DataColumn',
//        'attribute'=>'notes',
//    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
        'deleteOptions' => ['role' => 'modal-remote', 'title' => 'Delete',
            'data-confirm' => false, 'data-method' => false,// for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Are you sure?',
            'data-confirm-message' => 'Are you sure want to delete this item'],
    ],

];   