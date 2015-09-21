<?php
/**
 * Created by PhpStorm.
 * User: erenyildirim
 * Date: 28/12/14
 * Time: 18:40
 */

namespace admin;


class TagController extends \BaseController {
    public static function createTags($tagsTitles, $enum)
    {
        //Veritabanında tag varsa çek yoksa yarat.
        $tags = array();
        foreach ($tagsTitles as $tagTitle) {
            if ($tagTitle != '') {
                $tempObj = Tags::firstOrCreate(array('title' => $tagTitle, 'permalink' => self::toPermalink($tagTitle)));
                $tags[$tempObj->id] = ['enum' => $enum];
            }
        }
        return $tags;
    }

    private static function toPermalink($text)
    {
        $text = mb_strtolower(trim($text));
        $text = strtr($text, ['ç' => 'c', 'ö' => 'o', 'ğ' => 'g', 'ü' => 'u', 'ş' => 's', 'ı' => 'i']);
        $text = preg_replace('/[^a-z0-9-]/', '-', $text);
        $text = preg_replace('/-+/', "-", $text);
        return trim($text, '-');
    }

    //TODO DELETE THIS FUNCTION
    public static function changeAllPermalinks(){
        $episodes = Featured::get();
        foreach($episodes as $episode){
            $model = [
                'permalink' => self::toPermalink($episode->title)
            ];
            $episode->update($model);
        }
    }
}