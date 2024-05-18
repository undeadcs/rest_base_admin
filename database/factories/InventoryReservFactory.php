<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Inventory;
use App\Models\Reserv;
use App\Models\InventoryPrice;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InventoryReserv>
 */
class InventoryReservFactory extends Factory {
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition( ) : array {
		return [
			'inventory_id' => Inventory::factory( ),
			'reserv_id' => Reserv::factory( ),
			'inventory_price_id' => InventoryPrice::factory( ),
			'returned' => ( bool ) mt_rand( 0, 1 ),
			'comment' => fake( )->text( )
		];
	}
}
