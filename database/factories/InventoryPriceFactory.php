<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Inventory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InventoryPrice>
 */
class InventoryPriceFactory extends Factory {
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition( ) : array {
		return [
			'inventory_id' => Inventory::factory( ),
			'created_at' => fake( )->dateTime( )->format( 'Y-m-d H:i:s' ),
			'price' => fake( )->randomFloat( null, 1.0, 5000.0 )
		];
	}
}
