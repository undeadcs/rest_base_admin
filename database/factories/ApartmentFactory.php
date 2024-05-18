<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Apartment>
 */
class ApartmentFactory extends Factory {
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition( ) : array {
		return [
			'title' => fake( )->text( 64 ),
			'number' => mt_rand( 1, 100 ),
			'capacity' => mt_rand( 1, 4 ),
			'comment' => fake( )->text( )
		];
	}
}
