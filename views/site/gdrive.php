<?php

use johnitvn\ajaxcrud\CrudAsset;
use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\helpers\VarDumper;

/** @var $content
 * @var $error
 * @var $info
 * @var $client
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
<div class="row">
    <div class="col-xs-3">
        <?= Html::a('Добавить папку', ['create-dir'], ['class' => 'btn btn-success']); ?>
    </div>
    <div class="col-xs-3">
        <?= Html::a('Создать файл с текстом', ['create-file'], ['class' => 'btn btn-info']); ?>
    </div>
    <div class="col-xs-3">
        <?= Html::a('Удалить файл', ['delete-file'], ['class' => 'btn btn-warning']); ?>
    </div>
    <div class="col-xs-3">
        <?= Html::a('Удалить папку', ['delete-dir'], ['class' => 'btn btn-danger']); ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-3">
        <?= Html::a('Получить размер папки', ['get-size'], ['class' => 'btn btn-info']); ?>
    </div>
    <div class="col-xs-3">
        <div class="btn-toolbar">
            <div class="btn-group">
                <?= Html::a('Получить время папки', ['get-timestamp'], [
                    'role' => 'modal-remote',
                    'class' => 'btn btn-info',
                ]); ?>
            </div>
        </div>

    </div>
    <div class="col-xs-3">
        <?= Html::a('Загрузить файл', ['upload-file'], ['class' => 'btn btn-default']); ?>
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
        <?php if (!isset($access_token) || !$access_token):?>
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
            <h3><?php if (isset($uploads_file)) echo $uploads_file?></h3>
        </div>
        <div class="col-xs-4">

        </div>
    </div>
</div>
<?php
//$client->setClientId('667521552878-91u8dlqf19tgnbfhulohjmg3jmngvosg.apps.googleusercontent.com');
//$client->refreshToken('1/bVn_hdjxQ3CVTsXtNyQyBcEgpvAZPGxRAXikYT4GMZYH4kvl20bt_-QaK1xh3CCw');
//$client->setClientSecret('QmYeNv5dHMAgVADUdterUrpb');
//$client->setRedirectUri($redirect_uri);
//$client->addScope(Google_Service_Drive::DRIVE);
//
//// Запрос на подтверждение работы с Google-диском
//if (isset($_REQUEST['code'])) {
//    $token = $client->authorize($_REQUEST['code']);
//    $_SESSION['accessToken'] = $token;
//    header('Location:' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
//} elseif (!isset($_SESSION['accessToken'])) {
//    header('Location:' . filter_var($client->createAuthUrl(), FILTER_SANITIZE_URL));
//}
//
//// Присваиваем защитный токен для работы с Google-диском
//if (isset($_SESSION['accessToken'])){
//    $client->setAccessToken($_SESSION['accessToken']);
//    Yii::info($client->getAccessToken(), 'test');
//}
//$driveService = new Google_Service_Drive($client);
//$listFiles = $driveService->files->listFiles([
//    'fields' => 'nextPageToken, files(id, name, parents, fileExtension, mimeType, size, iconLink, thumbnailLink, webContentLink, webViewLink, createdTime)'
//]);
//// Пересобираем массив для добавления ключа parentId
//$files = [];
//foreach ($listFiles['modelData']['files'] as $k => $item) {
//    $files[$k] = $item;
//    $files[$k]['parentId'] = $item['parents'][0];
//    unset($files[$k]['parents']);
//}
//// Строим дерево элементов
//$buildTree = new buildTree();
//$tree = $buildTree->makeTree($files, '0AHCqnAQsA3IDUk9PVA');
//echo '<style type="text/css">
//  ul > li {
//    list-style: none;
//  }
//  ul > li > img {
//    margin-right: 3px;
//  }
//
//  .icon-new {
//    display: inline-block;
//    background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAIAAACQkWg2AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyZpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDIxIDc5LjE1NTc3MiwgMjAxNC8wMS8xMy0xOTo0NDowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTQgKFdpbmRvd3MpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkI1RTc1MjFBNjI1RTExRThCMEIwQzZBMUQyNjBFNTFEIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkI1RTc1MjFCNjI1RTExRThCMEIwQzZBMUQyNjBFNTFEIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6QjVFNzUyMTg2MjVFMTFFOEIwQjBDNkExRDI2MEU1MUQiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6QjVFNzUyMTk2MjVFMTFFOEIwQjBDNkExRDI2MEU1MUQiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7jjx+wAAABuklEQVR42pRSPYsUQRB9/TGO6y4KinuioGBgZGBgeNy/kLvEwOC4RC640NB/4I8QTEw88A+YCIrhqYFmBwfrutyyHzPd9WH13Gh4ck1N09NV9eq9qnaqiousOP/4dvr6QKsRFI5W15+8vLb19LyMr7u30vH7fHJIx++an2++74313OVlMK5u342DZbiyqO8M2iz/oTSdJdAEi1OwIq3re4+/PHtYjXwvrUg0rt452Hd1czuWW3YgURG3yg/2n0OkMwUzhPtf5xH9pxcHZwl2S5amRmfyw1noWRwTiJAzuLEyqKtm3cZk8GrmwYI009VMzW3RlEGMMES4jDhyEIfYEuI6EZpfOvms7byAUUFV27k/KGenlR9uuBv3SdBROj3CagrxhRV7GDGDK+eghZvXvJbFkf/9bTmfx2Vj7miiS5dMjDEp2CzcFwHbjai/BMmlraUCWWdbpdDTYDPGP1bd7q1UkMSIOWtBTR0wi1BS+tsf5hJtFXKWMhdPrFFCxKh2QxfIG7FgGgzeUIxh5o6kKfUIQOVtVHH8aOvDq8Oap2yOItEmK2bCYn5b6EYeA9qMm5s77qLP+48AAwD2V2eDUfQIogAAAABJRU5ErkJggg==");
//    width: 16px;
//    height: 16px;
//  }
//  .icon-upload{
//    display: inline-block;
//    background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAIAAACQkWg2AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyZpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDIxIDc5LjE1NTc3MiwgMjAxNC8wMS8xMy0xOTo0NDowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTQgKFdpbmRvd3MpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjBCNjg1MEQ0NjI1RjExRThBOTQ0RDIxMjczOUI5OTg0IiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjBCNjg1MEQ1NjI1RjExRThBOTQ0RDIxMjczOUI5OTg0Ij4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6MEI2ODUwRDI2MjVGMTFFOEE5NDREMjEyNzM5Qjk5ODQiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6MEI2ODUwRDM2MjVGMTFFOEE5NDREMjEyNzM5Qjk5ODQiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz6pslxlAAABb0lEQVR42oxSPUsDQRCd3du7yGkhpEghKQTthFTaKdraWdmqP8LKv2BnYWejjeQfiIWkEhWDoI2KihBJkUjI5+3uzDprQC/higwHNzP73ny8XeGcg5StniwWilMqCtm32tQ/B5Xd5zRAwaj1tN5e3nxsnrLf6sq313gMME7wRiKUOf4HLuMwg2BJIAF/3URORABCg5KrW8zqwFum4ziKtBF9I8iBNtjq9BcO8yOEEHJ7W/NOSJ6EQWRUtXEOpAhkPCPW1oOlwgrneUJCKF/cKj2gh/qNgBARyC8giSJkJq/hJGterd3BkMC6tTsK0VkrpABD4Ehax6eAKHh+a73P5S1juQRItKCsdi/vgU9482uWChsfVGHcV81yOB2lBExIdXvJ/dW/4N+9ZmkHhuOxXV82xlV6OnhLx8X9udk4nzTl71yCM52jfhqQcTUG+X3xaoGjyW4avSYB+RaTEf5EpAk7HJ+V27rlCVlv40eAAQDtXcpSmStOgAAAAABJRU5ErkJggg==");
//    width: 16px;
//    height: 16px;
//  }
//  .icon-delete {
//    display: inline-block;
//    background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAIAAACQkWg2AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyZpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDIxIDc5LjE1NTc3MiwgMjAxNC8wMS8xMy0xOTo0NDowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTQgKFdpbmRvd3MpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkU5NTRGNDZCNjI1RTExRTg4RUMwQTVGQzQyRTBGQTUxIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkU5NTRGNDZDNjI1RTExRTg4RUMwQTVGQzQyRTBGQTUxIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6RTk1NEY0Njk2MjVFMTFFODhFQzBBNUZDNDJFMEZBNTEiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6RTk1NEY0NkE2MjVFMTFFODhFQzBBNUZDNDJFMEZBNTEiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz5pfvFyAAAB7UlEQVR42mL8//8/AwNDyZqc84/PPHh7/+vPLwyoQIBLUFVM3VDWpMmvE8hlBGqInhG2+8aW/7////vN8P/ff4b/CNWMTAyMzIyMLEDEYKZosyV/F6PbLB12lu8/f/z89evfn99///75D7ETApiYGJmYGVlZmVnZmFjZmL/+ZmL5z/jN0UyegTiw8sBZlo8f/l5784VIDe/f/2d59uDHs3dXidTw+yMnyNPLFq9WUlMkqPrerftRsaEsQNbnz1+FRYSQ5cp2qwDJLtc7yIIXz14BkiANr189FxYShkvU7zc2k04GMjrPWjTYn+ZiE4CIf3j/Bqrh65cfggL8cA1fmF64mfgDGVNOLpcWQwTgr19/oRqA7nnx4qWwqMj3Xx852fgnuD0CkkDxNqdLn76/+/bnkzCX7NvXbyQkxEAxA8QystIP7z/88vnL/ofTs9Zqff785QsY/f3OkrlO6+TTpUA2UIGSsiJUg7S05NVrN37++DF5VwM/g9Kv7/8/fPwARECGLr8vUBAoBVQgryALdZKtg/XePftBlnz48+Df/fqtYcFaxd9+f5p4PBXieqDU4wePVFLioBqA4OOHz/de3czXXwThfvv4k4GBHc4FSn3+9BXChmrQ1FY9ve/i77//sEYZE8N/HR1tCBsgwABAIuuiYfgrmwAAAABJRU5ErkJggg==");
//    width: 16px;
//    height: 16px;
//  }
//</style>';
//// Вывод дерева
//printTree($tree);
//if (isset($_GET['action'])) {
//    switch ($_GET['action']) {
//        // Создание новой папки
//        case 'newdir':
//            if (isset($_GET['pid'])) {
//                include 'form-create-dir.php';
//                if ($_POST) {
//                    if (!empty($_POST['newFolder'])) {
//                        $fileMetadata = new Google_Service_Drive_DriveFile([
//                            'name' => htmlspecialchars($_POST['newFolder']),
//                            'parents' => [htmlspecialchars($_GET['pid'])],
//                            'mimeType' => 'application/vnd.google-apps.folder'
//                        ]);
//                        $driveService->files->create($fileMetadata, ['fields' => 'id']);
//                        header('Location:' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
//                    } else {
//                        die('Заполните название папки');
//                    }
//                }
//            }
//            break;
//        // Загрузка файлов
//        case 'upload':
//            if (isset($_GET['fid'])) {
//                include 'form-upload-file.php';
//                if (isset($_FILES['Files']) && is_uploaded_file($_FILES['Files']['tmp_name'][0]) && ($_FILES['Files']['error'][0] == 0)) {
//                    $files = normalizeFilesArray($_FILES);
//                    $fileMetadata = new Google_Service_Drive_DriveFile([
//                        'parents' => [htmlspecialchars($_GET['fid'])]
//                    ]);
//                    foreach ($files as $file) {
//                        $filePath = $file['tmp_name'];
//                        $mimeType = $file['type'];
//                        $fileMetadata->setName($file['name']);
//                        $fileMetadata->setDescription('Это документ ' . $mimeType);
//                        $fileMetadata->setMimeType($mimeType);
//                        $driveService->files->create($fileMetadata, [
//                            'data' => file_get_contents($filePath),
//                            'mimeType' => $mimeType,
//                            'uploadType' => 'multipart',
//                            'fields' => 'id'
//                        ]);
//                    }
//                    header('Location:' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
//                }
//            }
//            break;
//        case 'delete':
//            if (isset($_GET['fid'])) {
//                $driveService->files->delete(htmlspecialchars($_GET['fid']));
//                header('Location:' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
//            }
//            break;
//    }
//}
//ob_end_flush();
///** Функции */
//function printTree($tree)
//{
//    if (!is_null($tree) && count($tree) > 0) {
//        echo '<ul>';
//        foreach ($tree as $kn => $node) {
//            echo '<li>';
//            echo '<img src="'.$node['iconLink'].'" alt="">';
//            echo '<a href="'.$node['webViewLink'].'">';
//            if ($node['mimeType'] == 'application/vnd.google-apps.folder'){
//                echo '<b>' . $node['name'] . '</b>';
//            } else {
//                echo $node['name'];
//            }
//            echo '</a> ';
//            echo date('d.m.Y H:i:s', strtotime($node['createdTime']));
//            if ($node['mimeType'] == 'application/vnd.google-apps.folder'){
//                echo ' <a href="?action=newdir&pid='.urlencode($node['id']).'" title="Новая папка"><i class="icon-new"></i></a>';
//            }
//            echo ' <a href="?action=upload&fid=' . urlencode($node['id']) . '" title="Загрузить файл(ы)"><i class="icon-upload"></i></a>';
//            echo ' <a href="?action=delete&fid=' . urlencode($node['id']) . '" title="Удалить файл(ы)" onclick="return confirm(\'Вы действительно хотите удалить данный файл?\');"><i class="icon-delete"></i></a>';
//            printTree($node['children']);
//            echo '</li>';
//        }
//        echo '</ul>';
//    }
//}
///**
// * Приводим к нормальному виду глобальный массив $_FILES
// *
// * @param array $files
// * @return array
// */
//function normalizeFilesArray($files = [])
//{
//    $result = [];
//    foreach ($files as $file) {
//        if (!is_array($file['name'])) {
//            $result[] = $file;
//            continue;
//        }
//        foreach ($file['name'] as $idx => $name) {
//            $result[$idx] = [
//                'name'     => $name,
//                'type'     => $file['type'][$idx],
//                'tmp_name'   => $file['tmp_name'][$idx],
//                'error'   => $file['error'][$idx],
//                'size'     => $file['size'][$idx]
//            ];
//        }
//    }
//    return $result;
//}
///**
// * Вспомогательная функция для отладки
// *
// * @param $data
// */
//function varDumper($data){
//    echo '<pre>';
//    print_r($data);
//    echo '</pre>';
//}
?>
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


