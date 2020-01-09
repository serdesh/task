<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Messenger */
?>
<div class="messenger-view">
 
    <?php try {
      echo  DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'name',
            ],
        ]);
    } catch (Exception $e) {
        Yii::$app->session->setFlash('error', $e->getMessage());
        Yii::error($e->getTraceAsString(), __METHOD__);
    } ?>

</div>
