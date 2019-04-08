<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Boss */
?>
<div class="boss-view">
 
    <?php try {
       echo DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'name',
                'messenger_id',
                'messenger_number',
                'notes:ntext',
            ],
        ]);
    } catch (Exception $e) {
        Yii::$app->session->setFlash('error', $e->getMessage());
        Yii::error($e->getTraceAsString(), __METHOD__);
    } ?>

</div>
