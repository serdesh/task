<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var string $files */
/** @var \app\models\Project $project */
?>



    <?php $form = ActiveForm::begin(['options' => ['type' => 'multipart/form-data']]); ?>

    <?php if ($files):?>
        <h3><?php \yii\helpers\VarDumper::dump($files, 10, true); ?></h3>
    <?php endif; ?>

    <?php // echo $form->field($model, 'file')->fileInput()->label('Загрузить файл') ?>

    <?= $form->field($model, 'files[]')->fileInput(['multiple' => true])->label('Загрузить несколько файлов') ?>

    <?= $form->field($project, 'name')->textInput()->label('Проект') ?>

    <?= Html::button('Загрузить', ['type' => 'submit', 'class' => 'btn btn-info']) ?>

    <?php ActiveForm::end(); ?>
