<?php
/**
 * Created by PhpStorm.
 * User: erenyildirim
 * Date: 26/12/14
 * Time: 04:19
 */

namespace admin;


class Logs extends \Eloquent {
    protected $table = 'logs';
    public $timestamps = true;

    protected $dates = ['deleted_at'];
    protected $fillable = ['id', 'subject', 'message'];
    protected $visible = ['id', 'subject', 'message', 'created_at'];

    function subject(){
        return $this->belongsTo('\User', 'subject')->withTrashed();
    }
}