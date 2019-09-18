<?php

use app\models\Project;
use app\models\Task;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Task */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="task-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'project_id')->dropDownList(Project::getProjectList(), [
        'placeholder' => 'Выберите проект.'
    ]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'all_time')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'status')->dropDownList(Task::getStatusList()) ?>
        </div>
    </div>


    <?= $form->field($model, 'notes')->textarea(['rows' => 3]) ?>


    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update',
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>