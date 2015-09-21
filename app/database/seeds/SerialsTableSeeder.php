<?php

class SerialsTableSeeder extends Seeder {

	public function run()
	{
		//DB::table('serials')->delete();

		// Serial1
		Serials::create(array(
				'type' => 1,
				'title' => 'This is an example Seiral1'
			));

		// Serial2
		Serials::create(array(
				'type' => 1,
				'title' => 'This is an example Seiral2'
			));
	}
}