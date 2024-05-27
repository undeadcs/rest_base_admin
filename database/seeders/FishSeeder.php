<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FishSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 */
	public function run( ) : void {
		$this->call( [
			ApartmentsSeeder::class,
			InventoriesSeeder::class,
			CustomersSeeder::class
		] );
	}
}
