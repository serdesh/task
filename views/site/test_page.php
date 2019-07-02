<?php

use yii\bootstrap\Html;

?>

<div class="container">
    <div class="row">
        <div class="col-sm-10">
            <div class="alert alert-success call-alert" role="alert" data-template="alert">
                <div>Это сообщение предупреждения</div>
                <div class="other-elements-alert" data-template="elements">
                    <?= Html::a('Открыть карточку жильца', '', [
                        'class' => 'btn btn-warning pull-right',
                        'id' => 'resident-btn',
                    ]) ?>
                    <button id="close-msg-btn" type="button" class="close" data-dismiss="alert" aria-hidden="true">×
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-2">
            <?= Html::button('Клонировать', [
                'id' => 'clone-btn',
                'class' => 'btn btn-warning',
            ]) ?>
        </div>
    </div>

</div>

<?php
$script = <<<JS
$(document).ready(function(){
    var msg = '+7855465545'
    var clone_btn = $('#clone-btn');
     
    $(document).on('click', clone_btn, function() {
        var elements = $('[data-template="elements"]').clone();
        elements.removeAttr('data-template');
        var alert = $('[data-template="alert"]').clone();
        $('.container').append(alert.removeAttr('data-template').text('Входящий вызов от '+msg).show());
        alert.append(elements);
        console.log(alert);
    });
});
JS;

$this->registerJS($script, \yii\web\View::POS_READY);
