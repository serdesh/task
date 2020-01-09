<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var $model \app\models\Task */
?>

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-xs-12">
            <?= $form->field($model, 'json_text')->textarea(['rows' => 5]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-3 col-xs-offset-9">
            <?= Html::submitButton('Показать', [
                    'class' => 'btn btn-primary btn-block'
            ]) ?>
        </div>
    </div>
    <?php $form = ActiveForm::end(); ?>
    <?php
    if ($model->json_text) {
        $result = \yii\helpers\Json::decode($model->json_text);
        \yii\helpers\VarDumper::dump($result, 20, true);
    }
    ?>
