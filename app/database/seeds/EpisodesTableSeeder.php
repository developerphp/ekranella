<?php

class EpisodesTableSeeder extends Seeder {

	public function run()
	{
		//DB::table('episodes')->delete();

		// Episode 1
		Episodes::create(array(
				'season_id' => 1,
				'title' => 'Dexter'
			));
	}
}