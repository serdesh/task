<?php

/* @var $this yii\web\View */

use kartik\grid\GridView;

$this->title = 'Задачник';
?>
<div class="site-index">

    <?php
    try {
        echo GridView::widget([
            'moduleId' => 'gridviewKrajee', // change the module identifier to use the respective module's settings
            'dataProvider' => $dataProvider,
            'columns' => $columns,
            // other widget settings
        ]);
    } catch (Exception $e) {
        Yii::$app->session->setFlash('error', $e->getMessage());
        Yii::error($e->getTraceAsString(), __METHOD__);
    }
    ?>

</div>
