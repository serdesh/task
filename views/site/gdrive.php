<?php

use johnitvn\ajaxcrud\CrudAsset;
use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\helpers\VarDumper;

/** @var $content
 * @var $error
 * @var $info
 * @var $access_token
all commands https://github.com/creocoder/yii2-flysystem/blob/master/src/Filesystem.php
 */
CrudAsset::register($this);

?>
<h1>Это тестовая страница GoogleDrive</h1>
<h3>Ошибки</h3>
<?php
if (isset($error)) {
    VarDumper::dump($error, 10, true);
} ?>
<h3>Информация</h3>
<?php
if (isset($info)) {
    VarDumper::dump($info, 10, true);
}
?>
<div class="row" style="margin-bottom: 10px;">
    <div class="col-xs-3">
        <?= Html::a('Добавить папку', ['create-dir'], ['class' => 'btn btn-success btn-block']); ?>
    </div>
    <div class="col-xs-3">
        <?= Html::a('Создать файл с текстом', ['create-file'], ['class' => 'btn btn-info btn-block']); ?>
    </div>
    <div class="col-xs-3">
        <?= Html::a('Удалить файл', ['delete-file'], ['class' => 'btn btn-warning btn-block']); ?>
    </div>
    <div class="col-xs-3">
        <?= Html::a('Удалить папку', ['delete-dir'], ['class' => 'btn btn-danger btn-block']); ?>
    </div>
</div>
<div class="row" style="margin-bottom: 10px;">
    <div class="col-xs-3">
        <?= Html::a('Получить размер папки', ['get-size'], ['class' => 'btn btn-info btn-block']); ?>
    </div>
    <div class="col-xs-3">
        <?= Html::a('Получить время папки', ['get-timestamp'], [
            'role' => 'modal-remote',
            'class' => 'btn btn-info btn-block',
        ]); ?>
    </div>
    <div class="col-xs-3">
        <?= Html::a('Загрузить файл', ['upload-files'], ['class' => 'btn btn-default btn-block']); ?>
    </div>
    <div class="col-xs-3">
        <?= Html::a('Загрузить большой файл', ['upload-big-file'], ['class' => 'btn btn-info btn-block']); ?>
    </div>
</div>
<?php try {
    echo yii\authclient\widgets\AuthChoice::widget([
        'baseAuthUrl' => ['site/auth'],
        'popupMode' => false,
    ]);
} catch (Exception $e) {
    Yii::$app->session->setFlash('error', $e->getMessage());
    Yii::error($e->getTraceAsString(), __METHOD__);
} ?>

<div class="panel">

    <h3>Пример с Google books</h3>
    <?php
    //Yii::$app->controller->enableCsrfValidation = false;
    //$redirect_uri = "http://{$_SERVER ['HTTP_HOST']}/site/google-drive";
    //$redirect_uri = 'http://localhost/site/google-drive';
    //Yii::info($redirect_uri, 'test');

    $client = new Google_Client();
    $client->setApplicationName('My GoogleBook Test App');
    $client->setRedirectUri(Url::to('/site/google-drive'));
    $service = new Google_Service_Books($client);
    $optParams = ['filter' => 'free-ebooks'];
    $results = $service->volumes->listVolumes('Henry David Thoreau', $optParams);

    //VarDumper::dump($results, 10, true);
    foreach ($results as $item) {
        echo $item['volumeInfo']['title'], "<br />";
    }
    ?>
</div>

<div class="container">
    <h3>Токен</h3>
    <div class="row">
        <?php if (!isset($access_token) || !$access_token): ?>
            <div class="col-xs-12">
                <?= Html::a('Получить Token', ['/site/get-token'], ['class' => 'btn btn-info']) ?>
            </div>
        <?php else: ?>
            <div class="col-xs-12">
                <p><?= $access_token ? VarDumper::dump($access_token, 10, true) : 'Access Token not found' ?></p>
            </div>
        <?php endif; ?>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?php
            if (isset($auth_form)) {
                echo $auth_form;
            }
            ?>
        </div>
    </div>
    <div class="row">
        <h3>Список файлов на диске</h3>
        <div class="col-xs-12">
            <?php
            if (isset($files)) {
                foreach ($files as $file) {
                    echo 'Имя: ' . $file->name . ' (id:' . $file->id . ')<br>';
//                        VarDumper::dump($file, 10, true);
                }
            }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-4">
            <?php if (isset($folder)) VarDumper::dump($folder, 10, true) ?>
        </div>
        <div class="col-xs-4">
            <h3><?php if (isset($uploads_file)) echo $uploads_file ?></h3>
        </div>
        <div class="col-xs-4">

        </div>
    </div>
</div>
<?php
if (isset($content)) {
    VarDumper::dump($content, 10, true);
}
?>
<?php Modal::begin([
    "id" => "ajaxCrudModal",
    "footer" => "",// always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>


