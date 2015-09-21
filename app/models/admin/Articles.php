<?php

namespace admin;


class Article extends \Eloquent {

	protected $table = 'articles';
	public $timestamps = true;

	protected $dates = ['deleted_at'];
	protected $fillable = ['id','title', 'text', 'img', 'created_at', 'created_by', 'permalink', 'published', 'enum' ,'enumNumber', 'todays', 'views', 'is_author', 'guest_author'];
	protected $visible = ['id','title', 'text', 'img', 'created_at', 'created_by', 'permalink', 'published', 'enum','enumNumber', 'todays', 'views', 'is_author', 'guest_author'];

	function user(){
		return $this->belongsTo('\User','created_by')->withTrashed();
	}

	function tags(){
		return $this->belongstoMany('admin\Tags','tags_item','item_id', 'tag_id')->where('tags_item.enum',\Config::get('enums.articles'));
	}

	function featured_tags(){
		return $this->belongstoMany('admin\Tags','featured_item','item_id', 'tag_id')->where('featured_item.enum',\Config::get('enums.articles'));
	}
}