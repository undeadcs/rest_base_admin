<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Inventory;
use App\Models\InventoryPrice;

interface InventoryRepository {
	public function List( ) : Collection;
	public function Find( int $id ) : Inventory;
	public function Add( string $title ) : ?Inventory;
	public function PriceAdd( Inventory $inventory, float $priceValue ) : ?InventoryPrice;
}
