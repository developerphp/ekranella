<?php

namespace admin;


class Episodes extends \Eloquent {

	protected $table = 'episodes';
	public $timestamps = true;


	protected $fillable = ['id','season_id', 'title', 'text','number','summary','content', 'enum', 'img',  'type', 'enumNumber','permalink', 'published', 'created_by', 'airing_date', 'views', 'created_at', 'is_author', 'guest_author'];
	protected $visible = ['id','season_id', 'title', 'text','number','summary', 'content','enum', 'img', 'type',  'enumNumber' ,'permalink', 'published', 'created_by', 'airing_date', 'views', 'created_at', 'is_author', 'guest_author'];

	function user(){
		return $this->belongsTo('\User','created_by')->withTrashed();
	}

	public function season()
	{
		return $this->belongsTo('admin\Seasons', 'season_id');
	}

	function tags(){
		return $this->belongstoMany('admin\Tags','tags_item','item_id', 'tag_id')->where('tags_item.enum',\Config::get('enums.episodes'));
	}

	function featured_tags(){
		return $this->belongstoMany('admin\Tags','featured_item','item_id', 'tag_id')->where('featured_item.enum',\Config::get('enums.episodes'));
	}

	function gallery(){
		return $this->belongsToMany('admin\Galleries', 'gallery_item', 'item_id', 'gallery_id')->where('gallery_item.enum', \Config::get('enums.episodes'));
	}


}