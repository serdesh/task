<?php

use app\models\Task;
use app\models\User;
use kartik\base\Widget;
use kartik\select2\Select2;
use yii\bootstrap\Dropdown;
use yii\bootstrap\Html;
use yii\widgets\ActiveForm;

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

    <div class="panel panel-default">
        <div class="panel-heading">
            Обновление Select
        </div>
        <div class="panel-body">
            <?php
            $form = ActiveForm::begin();
            $model = Task::findOne(1);
            $items = [
                'Активный' => [
                    '0' => 'Админ',
                    '1' => 'Модератор',
                    '2' => 'Пользователь',
                ],
                'Отключен' => [
                    '3' => 'За нарушения',
                    '4' => 'Самостоятельно',
                ],
                'Удален' => [
                    '5' => 'Админом',
                    '6' => 'Самостоятельно',
                ],
            ];
            $params = [
                'prompt' => 'Выберите статус...',
//                'multiple' => true,
            ];
            echo $form->field($model, 'status')->dropDownList($items,$params);
            ActiveForm::end();
            ?>
            <?= Html::button('Обновить', ['class' => 'btn btn-success', 'id' => 'btn']) ?>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Работа с <?= Html::a('js-cookie', 'https://github.com/js-cookie/js-cookie', [
                'target' => '_blank'
            ]) ?></div>
        <div class="panel-body">
            <?= Html::checkbox('cookie-check', 0, ['id' => 'cookie-check']) ?>
            <label class="control-label" for="cookie-check"> Какой-то чекбокс</label>
        </div>
    </div>

<?php
$script = <<<JS
$(document).ready(function(){
    var msg = '+7855465545'
    var clone_btn = $('#clone-btn');
     
    // $(document).on('click', clone_btn, function() {
    //     var elements = $('[data-template="elements"]').clone();
    //     elements.removeAttr('data-template');
    //     var alert = $('[data-template="alert"]').clone();
    //     $('.container').append(alert.removeAttr('data-template').text('Входящий вызов от '+msg).show());
    //     alert.append(elements);
    //     console.log(alert);
    // });
    
    $(this).on('click', '#btn', function(){
       var data = [
        {
            id: 123,
            text: 'Новое значение'
        },
    ];
            $("#my-select").select2({data: data});
    });
    
    //куки-чекбокс
    let my_cookies = Cookies.noConflict();
    let cookie_checked = my_cookies.get('checked');
    let checkbox = $('#cookie-check');
    console.log('Cookie get: ' + my_cookies.get('checked'))
    console.log(typeof Boolean(cookie_checked));
    let num = Number(cookie_checked);
    console.log(typeof num);
    console.log(num);
    checkbox.prop('checked', Boolean(num));
    
    $(this).on('click', '#cookie-check', function (){
        if (checkbox.is(':checked')){
            my_cookies.set('checked', 1)
        } else {
            my_cookies.set('checked', 0)
        }
        console.log('Cookie set: ' + my_cookies.get('checked'))
    })
    
});
JS;

$this->registerJS($script, \yii\web\View::POS_READY);
