<?php
/**
 * Created by PhpStorm.
 * User: erenyildirim
 * Date: 08/01/15
 * Time: 17:49
 */

class FrontAdController extends \BaseController{
    public static function getRandomAd($sizeString){
       return \Ad::where('place', $sizeString)->where('is_active', 1)->orderBy(DB::raw('RAND()'))->first();
    }

    public function upAdClick($ad_id){
        $ad = \Ad::where('id', $ad_id)->first();
        admin\ViewsController::upAdClicks($ad);
    }
}