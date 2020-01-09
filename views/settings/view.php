<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Settings */
?>
<div class="settings-view">
 
    <?php
    try {
        echo DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'key',
                'value',
                'name',
                'description:ntext',
            ],
        ]);
    } catch (Exception $e) {
        Yii::error($e->getTraceAsString(), 'error');
    } ?>

</div>
