<?php
/**
 * Created by PhpStorm.
 * User: erenyildirim
 * Date: 10/01/15
 * Time: 19:11
 */

namespace admin;


class Liked extends \Eloquent
{
    protected $table = 'liked_item';
    public $timestamps = true;


    protected $fillable = ['enum', 'item_id', 'order'];
    protected $visible = ['id', 'enum', 'item_id', 'order', 'created_at'];


}