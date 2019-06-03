<?php

use yii\widgets\ActiveForm;
$url = 'http://promoyt/api/v1/upload-video';
//$url = 'http://promoyt.teo-crm.ru/api/v1/upload-file';
?>

<?php $form = ActiveForm::begin([
    'action' => $url,
    'options' => ['enctype' => 'multipart/form-data']
]); ?>

<div class="row">
    <div class="col-md-12 text-center">
        <h1>Форма отправки файла</h1>
        <b>Адрес: <?= $url?></b>
    </div>
</div>
<div class="clearfix">

</div>
<div class="row">
    <?= $form->field($model, 'date')->hiddenInput(['value' => '2019-06-02 12:55:55'])->label(false) ?>
    <?= $form->field($model, 'route')->hiddenInput(['value' => '27'])->label(false) ?>
</div>
<div class="row" style="margin-top: 40px; display: flex; align-items: center;">
    <div class="col-md-3">
        <?= $form->field($model, 'token')->label('Токен доступа') ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'type')->label('Тип файла')->dropDownList([0 => 'Паспорт', 1 => 'Сотрудник', 2 => 'Прочее', 3 => 'Видео']) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'file')->fileInput() ?>
    </div>
    <div class="col-md-2">
        <?= \yii\helpers\Html::submitButton('Отправить', ['class' => 'btn btn-info btn-block']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
