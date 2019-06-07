<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 05.06.2019
 * Time: 6:06
 */

namespace app\models;


use yii\base\Model;
use yii\helpers\VarDumper;

class Mail extends Model
{

    public function getImapTitle($str){
        $mime = imap_mime_header_decode($str);
        $title = "";
        foreach($mime as $m){
            VarDumper::dump($m, 10, true);

            if(!$this->check_utf8($m->charset)){
                $title .= $this->convert_to_utf8($m->charset, $m->text);
            } else {
                $title .= $m->text;
            }
        }
        return $title;
    }

    private function convert_to_utf8($in_charset, $str){
        return @iconv(strtolower($in_charset), "utf-8", $str);
    }

    private function check_utf8($charset){
        if(strtolower($charset) != "utf-8"){
            return false;
        }
        return true;
    }
}