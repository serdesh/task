<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Task */
?>
<div class="task-view">
 
    <?php try {
       echo DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'description:ntext',
                'start',
                'all_time',
                'status',
                'notes:ntext',
            ],
        ]);
    } catch (Exception $e) {
        Yii::$app->session->setFlash('error', $e->getMessage());
        Yii::error($e->getTraceAsString(), __METHOD__);
    } ?>

</div>
