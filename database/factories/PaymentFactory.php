<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory {
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition( ) : array {
		return [
			'created_at'	=> $this->faker->dateTime( ),
			'updated_at'	=> $this->faker->dateTime( ),
			'order_id'		=> Order::factory( ),
			'amount'		=> $this->faker->randomFloat( ),
			'comment'		=> $this->faker->text( )
		];
	}
}
