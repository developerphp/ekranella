<?php
/**
 * Created by PhpStorm.
 * User: erenyildirim
 * Date: 01/01/15
 * Time: 21:21
 */



class Rating extends \Eloquent {

    protected $table = 'ratings';
    public $timestamps = true;


    protected $fillable = ['type','order', 'title', 'channel', 'start', 'end', 'date', 'rating', 'share'];
    protected $visible = ['id','type', 'order', 'title', 'channel', 'start', 'end', 'date', 'rating'];

}