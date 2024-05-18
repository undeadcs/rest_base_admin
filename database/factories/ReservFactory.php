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
			'from' => fake( )->dateTime( ),
			'to' => fake( )->dateTime( ),
			'persons_number' => mt_rand( 1, 5 ),
			'comment' => fake( )->text( )
		];
	}
}
