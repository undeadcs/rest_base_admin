<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Inventory;
use App\Models\InventoryPrice;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DatabaseInventoryRepository implements InventoryRepository {
	public function List( int $page = 1, int $pageSize = 25 ) : LengthAwarePaginator {
		return Inventory::orderBy( 'title', 'asc' )->with( 'currentPrice' )->paginate( $pageSize, [ '*' ], 'page', $page );
	}
	
	public function ListOrders( Inventory $inventory, int $page = 1, int $pageSize = 25 ) : LengthAwarePaginator {
		return $inventory->orders( )->orderBy( 'id', 'desc' )->paginate( $pageSize, [ '*' ], 'page', $page );
	}
	
	public function ListOrdersWithApartment( Inventory $inventory, int $page = 1, int $pageSize = 25 ) : LengthAwarePaginator {
		return $inventory->orders( )
			->with( 'apartment' )
			->with( 'apartmentPrice' )
			->orderBy( 'id', 'desc' )
			->paginate( $pageSize, [ '*' ], 'page', $page );
	}
	
	public function GetAll( ) : Collection {
		return Inventory::orderBy( 'title', 'asc' )->with( 'currentPrice' )->get( );
	}
	
	public function ListByOrder( int $orderId ) : Collection {
		return Inventory::orders( )->where( 'order_id', $orderId )->get( );
	}
	
	public function Find( int $id ) : Inventory {
		return Inventory::findOrFail( $id );
	}
	
	public function Add( string $title, string $comment ) : ?Inventory {
		$inventory = new Inventory;
		$inventory->title = $title;
		$inventory->comment = $comment;
		
		return $inventory->save( ) ? $inventory : null;
	}
	
	public function Update( Inventory $inventory, string $title, string $comment ) : bool {
		$update = false;
		
		if ( $apartment->title != $title ) {
			$update = true;
			$apartment->title = $title;
		}
		if ( $apartment->comment != $comment ) {
			$update = true;
			$apartment->comment = $comment;
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
