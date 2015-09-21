<?php

class DatabaseSeeder extends Seeder {

	public function run()
	{
		Eloquent::unguard();

		$this->call('SerialsTableSeeder');
		$this->command->info('Serials table seeded!');

		$this->call('SeasonsTableSeeder');
		$this->command->info('Seasons table seeded!');

		$this->call('EpisodesTableSeeder');
		$this->command->info('Episodes table seeded!');
	}
}