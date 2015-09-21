<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEpisodesTable extends Migration {

	public function up()
	{
		Schema::create('episodes', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->integer('season_id')->unsigned()->index();
			$table->string('title', 255);
		});
	}

	public function down()
	{
		Schema::drop('episodes');
	}
}