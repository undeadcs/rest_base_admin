<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Apartment;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Inventory;

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
				->count( mt_rand( 2, 4 ) )
				->has( Customer::factory( ) )
				->for( $apartment )
				->for( $apartment->currentPrice )
				->hasPayments( mt_rand( 1, 5 ) )
				->hasAttached(
					Inventory::factory( )->hasPrices( 3 )->count( 3 ),
					fn( ) => [ 'comment' => fake( )->text( ) ]
				)
				->create( );
		} );
	}
}
