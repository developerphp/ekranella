<?php

namespace admin;

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class News extends \Eloquent {

	protected $table = 'news';
	public $timestamps = true;

    protected $dates = ['deleted_at'];
    protected $fillable = ['id', 'title', 'user_id', 'type', 'summary', 'text', 'img', 'enum', 'enumNumber','permalink', 'published', 'created_by', 'created_at', 'views','serial_id', 'is_author', 'guest_author'];
    protected $visible = ['id', 'title', 'user_id', 'type', 'summary', 'text',  'img',  'enum','enumNumber', 'permalink', 'published', 'created_by', 'created_at', 'views','serial_id', 'is_author', 'guest_author'];



    function user(){
        return $this->belongsTo('\User','created_by')->withTrashed();
    }

    function tags(){
        return $this->belongstoMany('admin\Tags','tags_item','item_id', 'tag_id')->where('tags_item.enum',\Config::get('enums.news'));
    }

    function featured_tags(){
        return $this->belongstoMany('admin\Tags','featured_item','item_id', 'tag_id')->where('featured_item.enum',\Config::get('enums.news'));
    }

    function gallery(){
        return $this->belongstoMany('admin\Galleries','gallery_item','item_id', 'gallery_id')->where('gallery_item.enum',\Config::get('enums.news'));
    }
    function serial(){
        return $this->belongsTo('admin\Serials','serial_id');
    }
}