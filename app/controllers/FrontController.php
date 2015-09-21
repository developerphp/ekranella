<?php
/**
 * Created by PhpStorm.
 * User: erenyildirim
 * Date: 08/01/15
 * Time: 18:47
 */

class FrontController extends \BaseController {
    public function __construct(){
        $config = admin\Config::select(['social', 'newsletterbox', 'home_title', 'rating_slide', 'rating_infos'])->first();
        $sqad = \FrontAdController::getRandomAd('Kare - 240x201');
        $vad = \FrontAdController::getRandomAd('Dikey - 184x449');
        $widead1 = \FrontAdController::getRandomAd('Üst Banner - 100x1000');
        $widead2 = \FrontAdController::getRandomAd('Alt Banner - 100x1000');
        $foreignad = \FrontAdController::getRandomAd('Yabancı - Kare');
        $domesticad = \FrontAdController::getRandomAd('Yerli - Kare');
        $data = [
            'fsocial' => unserialize($config->social),
            'sqad' => $sqad,
            'vad' => $vad,
            'widead1' => $widead1,
            'widead2' => $widead2,
            'foreignad' => $foreignad,
            'domesticad' => $domesticad,
            'newsletter' => $config->newsletterbox,
            'home_title' => $config->home_title,
            'rating_slider' => $config->rating_slide,
            'rating_infos' => unserialize($config->rating_infos)
        ];
        View::share($data);
    }
}