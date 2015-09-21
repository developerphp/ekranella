<?php
/**
 * Created by PhpStorm.
 * User: erenyildirim
 * Date: 26/12/14
 * Time: 04:22
 */
namespace admin;

use Carbon\Carbon;
use Laravelrus\LocalizedCarbon\LocalizedCarbon;
use Symfony\Component\HttpFoundation\Response;

class LogController extends \BaseController {

    public static function log($subject, $message){
        $log['message'] = $message;
        $log['subject'] = $subject;
        Logs::create($log);
    }

    public static function getLatest(){
        $latest_logs = Logs::orderBy('created_at', 'desc')->with('subject')->limit(20)->get();
        return $latest_logs;
    }

    public static function getLastForDash(){
        $latest_logs = Logs::orderBy('created_at', 'desc')->with('subject')->limit(4)->get();
        return $latest_logs;
    }

    public static function getPaginatedLogs(){
        $logs = Logs::orderBy('created_at', 'desc')->paginate(10);
        return $logs;
    }


    public static function timeConvert ($zaman){
        $zaman =  strtotime($zaman);
        $zaman_farki = time() - $zaman;
        $saniye = $zaman_farki;
        $dakika = round($zaman_farki/60);
        $saat = round($zaman_farki/3600);
        $gun = round($zaman_farki/86400);
        $hafta = round($zaman_farki/604800);
        $ay = round($zaman_farki/2419200);
        $yil = round($zaman_farki/29030400);
        if( $saniye < 60 ){
            if ($saniye == 0){
                return "az önce";
            } else {
                return $saniye .' saniye önce';
            }
        } else if ( $dakika < 60 ){
            return $dakika .' dakika önce';
        } else if ( $saat < 24 ){
            return $saat.' saat önce';
        } else if ( $gun < 7 ){
            return $gun .' gün önce';
        } else if ( $hafta < 4 ){
            return $hafta.' hafta önce';
        } else if ( $ay < 12 ){
            return $ay .' ay önce';
        } else {
            return $yil.' yıl önce';
        }
    }
}