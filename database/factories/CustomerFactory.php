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
			'name' => fake( )->name( ),
			'phone_number' => fake( )->phoneNumber( ),
			'car_number' => fake( )->regexify( '[A-Z0-9]{5}' ),
			'comment' => fake( )->text( )
		];
	}
}
