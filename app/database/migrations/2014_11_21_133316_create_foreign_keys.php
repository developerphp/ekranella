<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('seasons', function(Blueprint $table) {
			$table->foreign('serial_id')->references('id')->on('serials')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('episodes', function(Blueprint $table) {
			$table->foreign('season_id')->references('id')->on('seasons')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
	}

	public function down()
	{
		Schema::table('seasons', function(Blueprint $table) {
			$table->dropForeign('seasons_serial_id_foreign');
		});
		Schema::table('episodes', function(Blueprint $table) {
			$table->dropForeign('episodes_season_id_foreign');
		});
	}
}