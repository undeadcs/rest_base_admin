<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Inventory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventory>
 */
class InventoryFactory extends Factory {
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition( ) : array {
		return [
			'title' => fake( )->regexify( '[a-zA-Z]{5}' ),
			'how_pay' => fake( )->randomElement( [ Inventory::PAY_ONCE, Inventory::PAY_DAILY ] )
		];
	}
}
