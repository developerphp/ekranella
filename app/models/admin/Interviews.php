<?php

namespace admin;


class Interviews extends \Eloquent {

	protected $table = 'interviews';
	public $timestamps = true;


    protected $fillable = ['img','summary','text','title','created_by', 'permalink', 'published', 'enum' , 'enumNumber', 'created_at', 'views', 'serial_id', 'is_author', 'guest_author','subject'];
    protected $visible = ['id','img','summary', 'text','title','created_by', 'permalink', 'published', 'enum' , 'enumNumber', 'created_at', 'views', 'serial_id', 'is_author', 'guest_author','subject'];

    public function questions(){
        return $this->hasMany('admin\Questions', 'interview_id');
    }

    function tags(){
        return $this->belongstoMany('admin\Tags','tags_item','item_id', 'tag_id')->where('tags_item.enum',\Config::get('enums.interviews'));
    }

    function featured_tags(){
        return $this->belongstoMany('admin\Tags','featured_item','item_id', 'tag_id')->where('featured_item.enum',\Config::get('enums.interviews'));
    }

    function user(){
        return $this->belongsTo('\User','created_by')->withTrashed();
    }

    function serial(){
        return $this->belongsTo('admin\Serials','serial_id');
    }

}