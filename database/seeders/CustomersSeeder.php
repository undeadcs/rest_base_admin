<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomersSeeder extends Seeder {
	use WithoutModelEvents;
	
	/**
	 * Run the database seeds.
	 */
	public function run( ) : void {
		Customer::factory( )->count( 300 )->create( );
	}
}
