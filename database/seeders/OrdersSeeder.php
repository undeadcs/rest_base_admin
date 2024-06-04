<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Apartment;
use App\Models\Order;
use App\Models\Customer;

class OrdersSeeder extends Seeder {
	use WithoutModelEvents;
	
	/**
	 * Run the database seeds.
	 */
	public function run( ) : void {
		Order::factory( )
			->has( Apartment::factory( ) )
			->has( Customer::factory( ) )
			->count( 60 )
			->create( );
	}
}
