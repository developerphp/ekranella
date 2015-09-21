<?php
/**
 * Created by PhpStorm.
 * User: erenyildirim
 * Date: 26/12/14
 * Time: 12:35
 */

namespace admin;

class Slider extends \Eloquent {
    protected $table = 'slider';
    public $timestamps = true;

    protected $fillable = ['enum', 'item_id', 'img','text','title','order'];
    protected $visible = ['id', 'enum', 'item_id', 'img','text','title','order'];

    public static function item($id,$enum){

        if($enum==1)
          $item =  Episodes::select(\DB::raw('title , permalink, img, enum,  id, "' . \Config::get('enums.episodes') . '" as enumNumber'))->where('id',$id)->first()->toArray();

        else if($enum == 2)
           $item =  Interviews::select(\DB::raw('title, permalink, img,  id, "' . \Config::get('enums.interviews') . '" as enumNumber'))->where('id',$id)->first()->toArray(); 

        else if($enum == 3)
          $item =  News::select(\DB::raw('title, img, type, permalink, id, "' . \Config::get('enums.news') . '" as enumNumber'))->where('id',$id)->first()->toArray();
            //$item =  null; 
        else if($enum == 4)
            $item =  Article::select(\DB::raw('title,permalink,  img,  id, "' . \Config::get('enums.articles') . '" as enumNumber'))->where('id',$id)->first()->toArray();


        return $item;
    }

    public static function allSliders(){

        $sliders = parent::orderBy('order','ASC')->remember(1)->get()->toArray();
        $tempArray = [];
        foreach($sliders as $slide){
            $slide['item'] = self::item($slide['item_id'],$slide['enum']);
            $tempArray[] = $slide;
        }

        return $tempArray;
    }
}