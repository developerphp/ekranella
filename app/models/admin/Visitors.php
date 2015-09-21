<?php

namespace admin;


class Visitors extends \Eloquent {

	protected $table = 'visitors';
	protected $guarded = array();
	protected $fillable = ['fbID','name','surname','gender','newsletter','email'];
	protected $visible = ['id','fbID','name','surname','gender','newsletter','email','created_at'];
	public $timestamps = true;


}