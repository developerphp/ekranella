<?php

namespace admin;

class Seasons extends \Eloquent {

	protected $table = 'seasons';
	public $timestamps = true;

	protected $dates = ['deleted_at'];
	protected $fillable = ['id','serial_id','number', 'created_by'];
	protected $visible = ['id','serial_id','number', 'created_by'];

	public function episode()
	{
		$enum = \Config::get('enums.episode');
		return $this->hasMany('admin\Episodes', 'season_id')->where('episodes.enum', $enum['summary']);
	}

	public function special()
	{
		$enum = \Config::get('enums.episode');
		return $this->hasMany('admin\Episodes', 'season_id')->where('episodes.enum', $enum['specials']);
	}

	public function trailer()
	{
		$enum = \Config::get('enums.episode');
		return $this->hasMany('admin\Episodes', 'season_id')->where('episodes.enum', $enum['trailer']);
	}

	public function sgallery()
	{
		$enum = \Config::get('enums.episode');
		return $this->hasMany('admin\Episodes', 'season_id')->where('episodes.enum', $enum['sgallery']);
	}

	public function serial()
	{
		return $this->belongsTo('admin\Serials');
	}

}