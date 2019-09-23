<?php

use app\models\Project;
use app\models\Task;
use kartik\editable\Editable;
use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $data app\models\Task */

return [
//    [
//        'class' => 'kartik\grid\CheckboxColumn',
//        'width' => '20px',
//    ],
//    [
//        'class' => 'kartik\grid\SerialColumn',
//        'width' => '30px',
//    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'id',
        'label' => '#',
        'filter' => false,
        'vAlign' => 'middle',
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
        'value' => function ($data) {
            return Html::tag('p',  Task::formatMinutes($data->all_time), [
                'id' => 'time-' . $data->id,
            ]);
        },
        'format' => 'raw',
        'vAlign' => 'middle',
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
        'vAlign' => 'middle',
        'width' => '110px',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'value' => function (Task $data) {
            if (!$data->status) {
                if ($data->start) {
                    return Html::a('Стоп', ['#'], [
                        'class' => 'btn btn-danger start-btn',
                        'data-id' => $data->id,
                    ]);
                }
                return Html::a('Старт', ['#'], [
                    'class' => 'btn btn-success start-btn',
                    'data-id' => $data->id,
                ]);
            }
            return '';
        },
        'format' => 'raw',
        'vAlign' => 'middle',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'notes',
        'value' => function ($data) {
            if ($data->status == Task::TASK_STATUS_IN_WORK) {
                return $data->notes;
            }
            //Если задача завершена, комментарий не выводим
            return '';
        },
        'format' => 'nText',
    ],
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