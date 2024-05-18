<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Apartment;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ApartmentPrice>
 */
class ApartmentPriceFactory extends Factory {
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition( ) : array {
		return [
			'apartment_id' => Apartment::factory( ),
			'created_at' => fake( )->dateTime( )->format( 'Y-m-d H:i:s' ),
			'price' => fake( )->randomFloat( null, 1.0, 5000.0 )
		];
	}
}
