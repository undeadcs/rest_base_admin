<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Inventory;
use Illuminate\Database\Eloquent\Factories\Sequence;

class InventoriesSeeder extends Seeder {
	use WithoutModelEvents;
	
	/**
	 * Run the database seeds.
	 */
	public function run( ) : void {
		$titles = [ 'Мангал', 'Чайник', 'Кружка', 'Вилка', 'Тарелка', 'Одеяло', 'Плед' ];
		
		Inventory::factory( )
			->hasPrices( 2 )
			->count( count( $titles ) )
			->sequence( fn( Sequence $sequence ) => [ 'title' => $titles[ $sequence->index ] ] )
			->create( );
	}
}
