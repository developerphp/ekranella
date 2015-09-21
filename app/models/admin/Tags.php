<?php

namespace admin;


class Tags extends \Eloquent {

	protected $table = 'tags';
	public $timestamps = true;

    protected $fillable = ['id', 'title', 'permalink'];
    protected $visible = ['id', 'title', 'permalink'];


    function news(){
        return $this->belongstoMany('admin\News','tags_item','tag_id', 'item_id');
    }
}