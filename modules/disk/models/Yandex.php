<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 30.05.2019
 * Time: 13:56
 */

namespace app\modules\disk\models;


use Arhitector\Yandex\Disk;
use Arhitector\Yandex\Disk\Resource\Closed;
use Yii;
use yii\base\Model;
use yii\helpers\Url;
use yii\helpers\VarDumper;

class Yandex extends Model
{

    const CREATE_FOLDER = '';

    /**
     * Чёто делаем с диском
     */
    public static function requestSend()
    {
        // передать OAuth-токен зарегистрированного приложения.
        $disk = new Disk('AgAAAAAKWXLNAAWzB9G5rLRtnkmPlkBwAPLBzzs');

        /**
         * Получить Объектно Ориентированное представление закрытого ресурса.
         * @var  Closed $resource
         */
        $resource = $disk->getResource('file1.png');

        // проверить сущестует такой файл на диске ?
//        $resource->has(); // вернет, например, false
//        VarDumper::dump($resource->has(),10, true);

        // загрузить файл на диск под имененм "новый файл.txt".
//        $resource->upload(__DIR__ . '/файл в локальной папке.txt');

        $path_file = Url::to('@webroot/images/file1.png');

        Yii::info($path_file, 'test');

        $resource->upload($path_file);

        // файл загружен, вывести информацию.
        VarDumper::dump($resource, 10, true);

        // теперь удалить в корзину.
        $removed = $resource->delete();

        Yii::info('Removed: ' . $removed, 'test');
    }

}