<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Inventory;
use App\Models\InventoryPrice;

class DatabaseInventoryRepository implements InventoryRepository {
	public function List( ) : Collection {
		return Inventory::orderBy( 'title', 'asc' )->get( );
	}
	
	public function Find( int $id ) : Inventory {
		return Inventory::findOrFail( $id );
	}
	
	public function Add( string $title ) : ?Inventory {
		$inventory = new Inventory;
		$inventory->title = $title;
		
		return $inventory->save( ) ? $inventory : null;
	}
	
	public function Update( Inventory $apartment, string $title ) : bool {
		$update = false;
		
		if ( $apartment->title != $title ) {
			$update = true;
			$apartment->title = $title;
		}
		
		return !$update || $apartment->save( );
	}
	
	public function PriceAdd( Inventory $inventory, float $priceValue ) : ?InventoryPrice {
		$price = new InventoryPrice;
		$price->inventory_id = $inventory->id;
		$price->created_at = now( );
		$price->price = $priceValue;
		
		return $price->save( ) ? $price : null;
	}
}
