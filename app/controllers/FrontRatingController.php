<?php

/**
 * Created by PhpStorm.
 * User: erenyildirim
 * Date: 03/01/15
 * Time: 16:36
 */
class FrontRatingController extends \FrontController
{
    public function getRating($type = 'total', $skip=0)
    {

        $date = date('d/m/Y', time() - 60 * 60 * 24*($skip+1));
        $RatingTime = time() - 60 * 60 * 24*($skip+1);
        $ratingDate = iconv('latin5','utf-8',strftime("%d %B %Y %A", $RatingTime));

        $pickerDate = date('d/m/Y', time() - 60 * 60 * 24*($skip+1));

        $count =  (Rating::where('type', 1)->count()) / 20;
        $rating['total'] = Rating::where('type', 1)->where('date', $date)->orderBy('order')->limit(20)->get();
        $rating['ab'] = Rating::where('type', 2)->where('date', $date)->orderBy('order')->limit(20)->get();
        $rating['somera'] = Rating::where('type', 3)->where('date', $date)->orderBy('order')->limit(5)->get();


        if(count($rating['somera'])<1){
            $date = date('d/m/Y', time() - 60 * 60 * 24*($skip+2));
            $RatingTime = time() - 60 * 60 * 24*($skip+2);
            $ratingDate = iconv('latin5','utf-8',strftime("%d %B %Y %A", $RatingTime));

            $count =  (Rating::where('type', 1)->count()) / 20;
            $rating['total'] = Rating::where('type', 1)->where('date', $date)->orderBy('order')->limit(20)->get();
            $rating['ab'] = Rating::where('type', 2)->where('date', $date)->orderBy('order')->limit(20)->get();
            $rating['somera'] = Rating::where('type', 3)->where('date', $date)->orderBy('order')->limit(5)->get();
        }

        return View::make('front.rating.index', ['rating' => $rating,'social' => ConfigController::getSocial(), 'ratingDate' => $ratingDate, 'pickerDate' => $pickerDate, 'type' => $type, 'count' => $count, 'skip'=> $skip, 'headers' => ['title'=> 'Reytingler', 'description' => 'Reyting Listesi']]);
    }
}