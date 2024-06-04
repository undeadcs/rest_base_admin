<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Apartment;
use Illuminate\Database\Eloquent\Factories\Sequence;
use App\Enums\ApartmentType;

class ApartmentsSeeder extends Seeder {
	use WithoutModelEvents;
	
	/**
	 * Run the database seeds.
	 */
	public function run( ) : void {
		Apartment::factory( )
			->hasPrices( 3 )
			->count( 20 )
			->state( new Sequence( fn( Sequence $sequence ) => [
				'title' => 'База 1, домик #'.$sequence->index + 1,
				'number' => $sequence->index + 1,
				'type' => ApartmentType::House
			] ) )
			->create( );
		
		Apartment::factory( )
			->hasPrices( 3 )
			->count( 20 )
			->state( new Sequence( fn( Sequence $sequence ) => [
				'title' => 'База 1, Палатка #'.$sequence->index + 1,
				'number' => $sequence->index + 1,
				'type' => ApartmentType::TentPlace
			] ) )
			->create( );
	}
}
