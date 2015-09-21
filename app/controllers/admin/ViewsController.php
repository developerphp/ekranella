<?php
/**
 * Created by PhpStorm.
 * User: erenyildirim
 * Date: 28/12/14
 * Time: 18:40
 */

namespace admin;


class ViewsController extends \BaseController
{
    public static function upEpisodeViews($episode)
    {
        $model = [
            'views' => $episode->views + 1
        ];
        $episode->update($model);
    }

    public static function upSerialViews($serial)
    {
        $model = [
            'views' => $serial->views + 1
        ];
        $serial->update($model);
    }

    public static function upArticleViews($article)
    {
        $model = [
            'views' => $article->views + 1
        ];
        $article->update($model);
    }

    public static function upInterviewViews($interview)
    {
        $model = [
            'views' => $interview->views + 1
        ];
        $interview->update($model);
    }

    public static function upNewsViews($news){
        $model = [
            'views' => $news->views + 1
        ];
        $news->update($model);
    }

    public static function upAdViews($ad){
        $model = [
            'view' => $ad->view + 1
        ];
        $ad->update($model);
    }

    public static function upAdClicks($ad){
        $model = [
            'click' => $ad->click + 1
        ];
        $ad->update($model);
    }

    public static function upAuthorViews($author){
        $model = [
            'views' => $author->views + 1
        ];
        $author->update($model);
    }
}