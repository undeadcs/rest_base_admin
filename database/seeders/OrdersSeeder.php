<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Apartment;
use Illuminate\Database\Eloquent\Factories\Sequence;
use App\Models\Order;
use App\Models\Customer;

class OrdersSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 */
	public function run( ) : void {
		$apartments = Apartment::factory( )
			->hasPrices( 3 )
			->count( 20 )
			->state( new Sequence( fn( Sequence $sequence ) => [
				'title' => 'База 1, домик #'.$sequence->index + 1,
				'number' => $sequence->index + 1
			] ) )
			->create( );
		
		Order::factory( )
			->count( 60 )
			->recycle( $apartments )
			->has( Customer::factory( ) )
			->create( );
	}
}
