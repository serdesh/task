<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * @var array $file
 * @var array $files
 * @var string $token
 */

class UploadForm extends Model
{
    public $file;
    public $files;
    public $token;
    public $type;
    public $date;
    public $route;

    public function rules()
    {
        return [
            [
                ['file'],
                'file',
                'skipOnEmpty' => true,
                'extensions' => 'txt, ods, png, jpg, gif, sql, pdf, xls, xlsx, doc, docx, json, tar'
            ],
            [
                ['files'],
                'file',
                'skipOnEmpty' => true,
                'maxFiles' => 5,
                'extensions' => 'txt, ods, png, jpg, gif, sql, pdf, xls, xlsx, doc, docx, json, tar'
            ],
        ];
    }

    function upload()
    {
        Yii::info('Upload form start', 'test');

        if ($this->validate()){
            if (!is_dir('/uploads')) {
                mkdir('/uploads', 0777);
            }
            /** @var UploadedFile $file */
            foreach ($this->files as $file){
                $file->saveAs('uploads/' . $file->baseName . '.' . $file->extension);
            }
            return true;
        }
        return false;
    }

}