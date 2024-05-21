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
			'title'		=> $this->faker->text( 64 ),
			'number'	=> $this->faker->unique( )->randomNumber( ),
			'capacity'	=> $this->faker->randomNumber( ),
			'comment'	=> $this->faker->text( )
		];
	}
}
