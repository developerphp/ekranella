<?php

namespace admin;


class Featured extends \Eloquent {
    protected $table = 'featured';
    public $timestamps = true;


    protected $fillable = ['id', 'title', 'img', 'cover', 'created_by', 'permalink', 'published'];
    protected $visible = ['id','title', 'img', 'cover','created_by','permalink', 'published'];

    function tags(){
        return $this->belongstoMany('admin\Tags','tags_item','item_id', 'tag_id')->where('tags_item.enum',\Config::get('enums.featured'));
    }

    function news(){
        return $this->belongstoMany('admin\News','featured_item','featured_id', 'item_id')->where('featured_item.enum',\Config::get('enums.news'));
    }

    function interviews(){
        return $this->belongstoMany('admin\Interviews', 'featured_item', 'featured_id', 'item_id')->where('featured_item.enum', \Config::get('enums.interviews'));
    }

    function episodes(){
        return $this->belongstoMany('admin\Episodes', 'featured_item', 'featured_id', 'item_id')->where('featured_item.enum', \Config::get('enums.episodes'));
    }

    function articles(){
        return $this->belongstoMany('admin\Article', 'featured_item', 'featured_id', 'item_id')->where('featured_item.enum', \Config::get('enums.articles'));
    }

    function summary(){
        $episodesEnum = \Config::get('enums.episode');
        return $this->belongstoMany('admin\Episodes', 'featured_item', 'featured_id', 'item_id')->where('featured_item.enum', \Config::get('enums.episodes'))->where('episodes.enum', $episodesEnum['summary']);
    }

    function specials(){
        $episodesEnum = \Config::get('enums.episode');
        return $this->belongstoMany('admin\Episodes', 'featured_item', 'featured_id', 'item_id')->where('featured_item.enum', \Config::get('enums.episodes'))->where('episodes.enum', $episodesEnum['specials']);
    }

    function trailer(){
        $episodesEnum = \Config::get('enums.episode');
        return $this->belongstoMany('admin\Episodes', 'featured_item', 'featured_id', 'item_id')->where('featured_item.enum', \Config::get('enums.episodes'))->where('episodes.enum', $episodesEnum['trailer']);
    }

    function sgallery(){
        $episodesEnum = \Config::get('enums.episode');
        return $this->belongstoMany('admin\Episodes', 'featured_item', 'featured_id', 'item_id')->where('featured_item.enum', \Config::get('enums.episodes'))->where('episodes.enum', $episodesEnum['sgallery']);
    }

    function user(){
        return $this->belongsTo('\User','created_by')->withTrashed();
    }

}