<?php

namespace admin;

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Channel extends \Eloquent {

	protected $table = 'channels';
	public $timestamps = true;

	protected $dates = ['deleted_at'];
	protected $fillable = ['title','id'];
	protected $visible = ['title','id'];


}