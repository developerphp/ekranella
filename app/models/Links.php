<?php
/**
 * Created by PhpStorm.
 * User: coskudemirhan
 * Date: 04/01/15
 * Time: 22:35
 */
class Links extends Eloquent
{
    protected $table = 'links';
    protected $fillable = ['link','type','serial','serial_id','season','season_id','episode','lock','content','object'];
    public $timestamps = true;
}