<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Inventory;
use App\Models\Order;

class InventoryWithOrdersSeeder extends Seeder {
	use WithoutModelEvents;
	
	/**
	 * Run the database seeds.
	 */
	public function run( ) : void {
		Inventory::factory( )
			->hasPrices( 1 )
			->hasAttached(
				Order::factory( )->count( 30 ),
				[ 'comment' => fake( )->text( ) ]
			)
			->count( 3 )
			->create( );
	}
}
