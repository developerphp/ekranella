<?php

class SeasonsTableSeeder extends Seeder {

	public function run()
	{
		//DB::table('seasons')->delete();

		// Season1
		Seasons::create(array(
				'serial_id' => 1
			));
	}
}