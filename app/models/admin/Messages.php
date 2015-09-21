<?php

namespace admin;


class Messages extends \Eloquent {

	protected $table = 'messages';
	public $timestamps = true;

	protected $dates = ['deleted_at'];
	protected $fillable = ['id','text', 'created_by'];
	protected $visible = ['id','text',  'created_by', 'created_at', 'created_by'];

	function user(){
		return $this->belongsTo('\User','created_by')->withTrashed();
	}
}