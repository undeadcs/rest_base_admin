<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;
use App\Models\Apartment;
use App\Models\ApartmentPrice;
use App\Enums\OrderStatus;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory {
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition( ) : array {
		$apartment = Apartment::factory( )->hasPrices( 2 );
		
		return [
			'created_at'			=> $this->faker->dateTime( ),
			'updated_at'			=> $this->faker->dateTime( ),
			'customer_id'			=> Customer::factory( ),
			'apartment_id'			=> $apartment,
			'apartment_price_id'	=> ApartmentPrice::factory( )->has( $apartment ),
			'status'				=> $this->faker->randomElement( OrderStatus::class )->value,
			'from'					=> Carbon::parse( $this->faker->dateTime( )->format( 'Y-m-d H:i:00' ) ),
			'to'					=> Carbon::parse( $this->faker->dateTime( )->format( 'Y-m-d H:i:00' ) ),
			'persons_number'		=> mt_rand( 1, 10 ),
			'comment'				=> $this->faker->text( )
		];
	}
}
