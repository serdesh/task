<?php

use yii\helpers\Html;

?>

<h3>Kartik mPDF</h3>
<div class="row">
    <div class="col-md-12">
        <?= Html::a('Кнопка', '#', ['class' => 'btn btn-default']) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?php require (__DIR__ . '/_mpdf_form.php')?>
    </div>
</div>
<?php

?>
