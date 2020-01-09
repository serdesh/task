<?php

use yii\helpers\Html;

?>

<h3>Yandex</h3>
<div class="row">
    <div class="col-md-12">
        <?= Html::a('Показать карту', ['get-map'], ['class' => 'btn btn-default']) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12 text-center">
        <?php
        if (isset($map_image)) {
            echo Html::img($map_image, ['alt' => 'map']);
        } else {
            echo 'No image';
        }
        ?>
    </div>
</div>

