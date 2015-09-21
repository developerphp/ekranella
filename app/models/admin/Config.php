<?php

namespace admin;


class Config extends \Eloquent {

	protected $table = 'config';
	public $timestamps = true;

	protected $fillable = ['social', 'newsletterbox', 'home_title', 'home_tags', 'home_description', 'rating_slide', 'rating_infos'];
	protected $visible = ['id','social', 'newsletterbox', 'home_title', 'home_tags', 'home_description', 'rating_slide', 'rating_infos'];

}