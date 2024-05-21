<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;
use App\Models\Apartment;
use App\Models\ApartmentPrice;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reserv>
 */
class ReservFactory extends Factory {
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition( ) : array {
		return [
			'apartment_id' => Apartment::factory( ),
			'customer_id' => Customer::factory( ),
			'apartment_price_id' => ApartmentPrice::factory( ),
			'from' => $this->faker->dateTime( ),
			'to' => $this->faker->dateTime( ),
			'persons_number' => $this->faker->randomNumber( ),
			'comment' => $this->faker->text( )
		];
	}
}
