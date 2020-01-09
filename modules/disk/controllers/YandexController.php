<?php

namespace app\modules\disk\controllers;

use app\modules\disk\models\Yandex;
use yii\web\Controller;

/**
 * Default controller for the `disk` module
 */
class YandexController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionTest()
    {
        Yandex::requestSend();
    }
}
