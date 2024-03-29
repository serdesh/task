<?php

use app\models\Task;
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
                [
                    'attribute' => 'project_id',
                    'value' => function ($data) {
                        return $data->project->name;
                    }
                ],
                'description:ntext',
//                'start',
                'done_date:date',
                'all_time',
                [
                    'attribute' => 'status',
                    'value' => function($data){
                       return Task::getStatusName($data->status);
                    }
                ],
                'notes:ntext',
                'paid',
                'paid_date:date',
                'agreed_price',
                'plan_time',
                [
                    'attribute' => 'parent_task_id',
                    'value' => $model->parentTask->description ?? null,
                ],

            ],
        ]);
    } catch (Exception $e) {
        Yii::$app->session->setFlash('error', $e->getMessage());
        Yii::error($e->getTraceAsString(), __METHOD__);
    } ?>

</div>
