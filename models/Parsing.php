<?php

namespace app\models;


use phpQuery;
use Yii;
use yii\helpers\Url;

class Parsing
{
    public $url;


    public function parse()
    {

        $html = file_get_contents(Url::to(['@web/uploads/parse_doc.html'], true));

        $dom = phpQuery::newDocumentHTML($html);

        $menu = $dom->find('.menu-kontrolnye-stranicy-container');
//        Yii::info($menu->html(), 'test');

//        $lis = $menu->children('li');
//
//        foreach ($lis as $li){
//            $li1 = pq($li);
//            Yii::info($li1->find('a')->attr('href'), 'test');
//            Yii::info($li1->attr('id'), 'test');
//            Yii::info($li1->html(), 'test');
//        }

        $target_categories = ['menu-item-22118' => 'СИНТЕТИЧЕСКИЕ МОЛЕКУЛЫ И БАЗЫ', 'menu-item-22114' =>  'ПРИРОДНЫЕ ИЗОЛЯТЫ, ЭФИРНЫЕ МАСЛА, АБСОЛЮТЫ'];

        $target_hrefs = [];

        foreach ($target_categories as $key => $target_category){
            Yii::info($target_category, 'test');
           $categories = $menu->find("[id=" . $key . "]");

//           Yii::info($categories->html(), 'test');

            $targets = [];

           foreach ($categories as $category){
               $cat = pq($category);
//               $targets[$cat->find('a')->attr('href')] = $cat->find('a')->text();
               array_push($targets, $cat->find('li a')->attr('href'));
           }
            Yii::info($targets, 'test');
        }

        $submenu = $dom->find('#menu-item-22118');


        $href = $menu->find('li > a')->append(PHP_EOL)->attr('href');
        $text = $menu->find('li > a')->append(PHP_EOL)->html();

        $href = explode(PHP_EOL, $href);
        $text = explode(PHP_EOL, $text);

        return $href;
    }

}