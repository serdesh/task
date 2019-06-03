<?php

use johnitvn\ajaxcrud\CrudAsset;
use yii\httpclient\Client;

$data = [
    'response_type' => 'token',
    'client_id' => '7622a31534b847209784c0687044b514',
    'redirect_uri' => 'http://localhost/site/yandex-disk',
];

Yii::info(http_build_query($data), 'test');

$client = new Client(['baseUrl' => 'https://oauth.yandex.ru']);
$response = $client->createRequest()
    ->setUrl('/authorize?' . http_build_query($data))
    ->addHeaders(['content-type' => 'application/json'])
//    ->setContent()
    ->send();

echo $response->content;

