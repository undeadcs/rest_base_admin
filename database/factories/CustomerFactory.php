<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory {
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition( ) : array {
		return [
			'name'			=> $this->faker->name( ),
			'phone_number'	=> $this->faker->phoneNumber( ),
			'car_number'	=> $this->faker->regexify( '[A-Z][0-9]{3}[A-Z]{2}[0-9]{2,3}' ),
			'comment'		=> $this->faker->text( )
		];
	}
}
