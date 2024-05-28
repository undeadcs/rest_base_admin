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
		$this->call( ApartmentsSeeder::class );
		
		$apartments = Apartment::all( );
		
		$apartments->each( function( Apartment $apartment ) {
			Order::factory( )
				->count( mt_rand( 1, 3 ) )
				->has( Customer::factory( ) )
				->for( $apartment )
				->for( $apartment->currentPrice )
				->create( );
		} );
	}
}
