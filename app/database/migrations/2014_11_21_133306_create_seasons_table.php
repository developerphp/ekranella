<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSeasonsTable extends Migration {

	public function up()
	{
		Schema::create('seasons', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->integer('serial_id')->unsigned()->index();
		});
	}

	public function down()
	{
		Schema::drop('seasons');
	}
}