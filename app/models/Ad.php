<?php
/**
 * Created by PhpStorm.
 * User: coskudemirhan
 * Date: 04/01/15
 * Time: 22:35
 */
class Ad extends Eloquent
{
    protected $table = 'ad';
    protected $guarded = ['id'];
    public $timestamps = false;
}