<?php

use johnitvn\ajaxcrud\CrudAsset;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;

CrudAsset::register($this);

/* @var string $code Код доступа */

?>

    <h3>Yandex Диск</h3>
    <div class="row">
        <div class="col-md-4">
            <?= Html::a('Обновить данные', ['yandex-disk'], [
                'class' => 'btn btn-primary',
                'data-ajax' => true,
            ]) ?>
        </div>
        <?php if (!is_file(Url::to('@app/token.txt'))): ?>
            <div class="col-md-4">
            <?= Html::a('Открыть доступ к яндекс диску', Url::to(['yandex-disk', 'oauth' => 1]), [
                'class' => 'btn btn-info',
                'role' => 'modal-remote',
            ]) ?>
        </div>
            <div id="token-field" class="col-md-4 text-center">
                <p>Токен для доступа: <b id="token"></b></p>
                <?= Html::a('Сохранить токен', Url::to(['save-token']), [
                    'id' => 'save-token-btn',
                    'class' => 'btn btn-success btn-block'
                ]) ?>
            </div>
        <?php else: ?>
        <div class="col-md-4">
            <p>Токен в наличии. Действие не требуется</p>
        </div>
        <?php endif; ?>
    </div>
<!--    --><?php //if (isset($oauth)):?>
<!--    <div class="row">-->
<!--        <div class="col-md-12">-->
<!--            --><?php //require (__DIR__ . '/_oauth_form.php')?>
<!--        </div>-->
<!--    </div>-->
<!--    --><?php //endif; ?>
    <div class="row">
        <div class="col-md-2">
            <?= Html::button('Общий объем', ['id' => 'total-space']) ?>
        </div>
        <div id="total-space-text" class="col-md-10">

        </div>
    </div>


<?php Modal::begin([
    "id" => "ajaxCrudModal",
    "footer" => "",// always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>

<?php
$this->registerJS(<<<JS
    $(document).ready(function() {
        var token = /access_token=([^&]+)/.exec(document.location.hash)[1];
        if (token){
            $('#token-field').show();
            $('#token').text(token);
            var save_btn = $('#save-token-btn');
            var btn_href = save_btn.attr('href');
            //Добавляем токен в ссылку
            save_btn.attr('href', btn_href + '?token=' + token);
        } else {
            $('#token-field').hide();
        }
    })
JS
);

