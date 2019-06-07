<?php

use yii\widgets\ActiveForm;
$url = 'http://promoyt/api/v1/upload-video';
//$url = 'http://promoyt.teo-crm.ru/api/v1/upload-file';
?>
<?php if (!$url): //Условие добавлено для отключения стандартной формы?>
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
<? endif; ?>

<div class="row">
    <div class="col-md-12">
        <h2>На гольном php</h2>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <!-- Тип кодирования данных, enctype, ДОЛЖЕН БЫТЬ указан ИМЕННО так -->
        <form enctype="multipart/form-data" action="http://promoyt/api/v1/upload-video" method="POST">
            <!-- Поле MAX_FILE_SIZE должно быть указано до поля загрузки файла -->
            <input type="hidden" name="MAX_FILE_SIZE" value="3000000000" />
            <!-- Название элемента input определяет имя в массиве $_FILES -->
            Отправить этот файл: <input name="file" type="file" />
            <input type="text" name="token" value="123">
            <input type="text" name="date" value="2019-06-04 11:00:00">
            <input type="text" name="route" value="27">
            <input type="submit" value="Отправить файл" />
        </form>
    </div>
</div>
