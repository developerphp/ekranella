<?php
/**
 * Created by PhpStorm.
 * User: erenyildirim
 * Date: 26/12/14
 * Time: 12:35
 */

namespace admin;

class FeaturedItem extends \Eloquent {
    protected $table = 'featured_item';
    public $timestamps = true;

    protected $fillable = ['enum', 'featured_id', 'item_id', 'tag_id'];
    protected $visible = ['id', 'enum', 'featured_id', 'item_id', 'tag_id'];

}