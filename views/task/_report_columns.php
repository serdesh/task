<?php

use app\models\Project;
use app\models\Task;
use kartik\editable\Editable;
use yii\bootstrap\Html;

/* @var $data app\models\Task */

return [

    [
        'class' => '\kartik\grid\SerialColumn',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'paid',
        'filter' => false,
        'value' => function (Task $model) {
            $txt = $model->paid ? 'Да' : '<b>Нет</b>';
            return Html::a($txt, ['view', 'id' => $model->id], ['role' => 'modal-remote', 'title' => 'Просмотр информации о задаче']);
        },
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'format' => 'raw'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'done_date',
        'filter' => false,
//        'value' => function (Task $model) {
//    Yii::debug($model->done_date, 'test');
//
//        },
        'vAlign' => 'middle',
        'format' => 'date',
    ],
    [
        'class' => '\kartik\grid\EditableColumn',
        'attribute' => 'description',
        'editableOptions' => [
            'asPopover' => false,
            'inputType' => Editable::INPUT_TEXTAREA,
            'size' => 'lg',
            'options' => [
                'class' => 'form-control',
                'rows' => 5,
                'placeholder' => 'Введите задачу...',
            ]
        ],
        'vAlign' => 'middle',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'project_id',
        'filter' => Project::getProjectList(),
        'value' => function ($data) {
            if (!$data->project_id) {
                return Html::activeDropDownList($data, 'project_id', Project::getProjectList(), [
                    'id' => 'project-dropdown',
                    'data-id' => $data->id,
                    'style' => 'width: 100%; border-radius: 4px; padding: 5px;',
                    'prompt' => 'Выберите проект',
                ]);
            }
            Yii::setAlias('@example', $data->project->url);
            return Html::a($data->project->name, '@example', ['target' => '_blank']);
        },
        'format' => 'raw',
        'vAlign' => 'middle',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'all_time',
        'label' => 'Время (мин.)',
//        'value' => function ($data) {
//            return Task::formatMinutes($data->all_time);
//        },
        'hAlign' => 'center',
        'vAlign' => 'middle',
    ],

    [
        'attribute' => 'agreed_price'
    ]

//    [
//        'class' => '\kartik\grid\DataColumn',
//        'attribute' => 'status',
//        'value' => function ($data) {
//            if (!$data->status) {
//                return Html::activeDropDownList($data, 'status', Task::getStatusList(), [
//                    'id' => 'statuses-dropdown',
//                    'data-id' => $data->id,
//                    'style' => 'width: 100%; border-radius: 4px; padding: 5px;'
//                ]);
//            }
//            return Task::getStatusName($data->status);
//        },
//        'format' => 'raw',
//        'vAlign' => 'middle',
//        'width' => '110px',
//    ],
//    [
//        'class' => 'kartik\grid\ActionColumn',
//        'dropdown' => false,
//        'vAlign' => 'middle',
//        'urlCreator' => function ($action, $model, $key, $index) {
//            return Url::to([$action, 'id' => $key]);
//        },
//        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
//        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
//        'deleteOptions' => ['role' => 'modal-remote', 'title' => 'Delete',
//            'data-confirm' => false, 'data-method' => false,// for overide yii data api
//            'data-request-method' => 'post',
//            'data-toggle' => 'tooltip',
//            'data-confirm-title' => 'Are you sure?',
//            'data-confirm-message' => 'Are you sure want to delete this item'],
//    ],

];   