<?php

use yii\helpers\Html;
use yii\helpers\Url;

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
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'name',
//        'value' => function ($data) {
//            Yii::setAlias('@example', 'http://' . $data->name . '/');
//            return Html::a( $data->name, '@example', ['target' => '_blank']);
//        },
//        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'url',
        'value' => function ($data) {
            return Html::a($data->url, Url::to($data->url), ['target' => '_blank']);
        },
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'local_url',
        'value' => function ($data) {
            return Html::a($data->local_url, Url::to($data->local_url), ['target' => '_blank']);
        },
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'login',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'password',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'boss_id',
        'value' => function($data){
            return $data->boss->name;
        },
        'label' => 'Заказчик',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'exclude_statistic',
        'label' => 'Исключать из статистики',
        'value' => function($data){
            if (!$data->exclude_statistic){
                return Html::tag('p', '<b>Нет</b>', ['class' => 'text-success']);
            }
            return Html::tag('p', '<b>Да</b>', ['class' => 'text-danger']);
        },
        'format' => 'raw',
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