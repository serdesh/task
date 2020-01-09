<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Project */
?>
<div class="project-view">

    <?php try {
        echo DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'name',
                'url:ntext',
                'login',
                'password',
                'created_at',
                [
                    'attribute' => 'boss_id',
                    'label' => 'Заказчик',
                    'value' => $model->boss->name,
                ]
            ],
        ]);
    } catch (Exception $e) {
        Yii::$app->session->setFlash('error', $e->getMessage());
        Yii::error($e->getTraceAsString(), __METHOD__);
    } ?>

</div>
