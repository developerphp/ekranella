<?php

namespace admin;


class Questions extends \Eloquent {

	protected $table = 'interviews_questions';
	public $timestamps = true;


    protected $fillable = ['questionText','answerText','interview_id'];
    protected $visible = ['id','interview_id','questionText','answerText'];

}