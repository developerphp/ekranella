<?php

namespace admin;


class Galleries extends \Eloquent {

	protected $table = 'galleries';
	public $timestamps = true;

	protected $fillable = ['img','text','order'];
	protected $visible = ['id','img','text','order'];

}