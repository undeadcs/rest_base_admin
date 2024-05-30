<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Apartment;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Inventory;
use Illuminate\Database\Eloquent\Factories\Sequence;

class OrdersSeeder extends Seeder {
	use WithoutModelEvents;
	
	/**
	 * Run the database seeds.
	 */
	public function run( ) : void {
		$this->call( [ ApartmentsSeeder::class, CustomersSeeder::class ] );
		
		$apartments = Apartment::all( );
		
		$apartments->each( function( Apartment $apartment ) {
			Order::factory( )
				->for( $apartment )
				->for( $apartment->currentPrice )
				->hasPayments( 2 )
				->hasAttached(
					Inventory::factory( )->hasPrices( 2 )->count( 2 ),
					fn( ) => [ 'comment' => fake( )->text( ) ]
				)
				->count( 1 )
				->sequence( fn( Sequence $sequence ) => [
					'customer_id' => Customer::all( )->random( ),
					'from' => fake( )->dateTimeBetween( '-1 week', '+1 week' ),
					'to' => fake( )->dateTimeBetween( '+2 week', '+3 week' )
				] )
				->create( );
		} );
	}
}
