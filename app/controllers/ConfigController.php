<?php

class ConfigController extends BaseController
{

    public function getIndex()
    {
        return View::make('hello');
    }

    public static function getSocial()
    {
        $config = admin\Config::select(['social'])->first();

        $social = unserialize($config->social);

        return $social;
    }


    public static function  getRatingSlider()
    {
    $config = admin\Config::select(['rating_infos'])->first();
    $rating_infos = unserialize($config->rating_infos);

    return $rating_infos;

    }

    public  static function updateRatingSlider($rating_infos = null){
        $currentUser = \Auth::user();

        $model['rating_infos'] = serialize($rating_infos);

        try {
            $config = admin\Config::first();
            $config->update($model);
            admin\LogController::log($currentUser->id, ' sitenin genel ayarlarında değişiklikler yaptı');
            $alert = "Değişiklikler başarıyla uygulandı";

            return $alert;
        } catch (Exception $err) {
            return $err->getMessage();
        }

    }

    public function getNewsletter()
    {
        $config = admin\Config::select(['newsletterbox'])->first();
        return $config->newsletterbox;
    }

}
