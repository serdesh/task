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

    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'project_id')->dropDownList(Project::getProjectList(), [
                'prompt' => 'Выберите проект.'
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'agreed_price')->textInput() ?>
        </div>
    </div>

    <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'all_time')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'plan_time')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'status')->dropDownList(Task::getStatusList()) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'parent_task_id')->dropDownList($model->getTasks(), [
                'prompt' => 'Выберите задачу...'
            ]) ?>
        </div>
    </div>

    <?= $form->field($model, 'notes')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'paid')->checkbox() ?>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update',
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>