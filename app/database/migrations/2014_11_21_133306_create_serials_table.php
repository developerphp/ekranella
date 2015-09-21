<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSerialsTable extends Migration {

	public function up()
	{
		Schema::create('serials', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->enum('type', array('1', '2', '3'))->index();
			$table->string('title', 255);
		});
	}

	public function down()
	{
		Schema::drop('serials');
	}
}