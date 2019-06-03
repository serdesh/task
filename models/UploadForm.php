<?php

namespace app\models;

use yii\base\Model;

class UploadForm extends Model
{
    public $file;
    public $token;
    public $type;
    public $date;
    public $route;
}