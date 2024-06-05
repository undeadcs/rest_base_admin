<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Inventory;
use App\Models\InventoryPrice;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface InventoryRepository {
	public function List( int $page = 1, int $pageSize = 25 ) : LengthAwarePaginator;
	public function ListOrders( Inventory $apartment, int $page = 1, int $pageSize = 25 ) : LengthAwarePaginator;
	public function GetAll( ) : Collection;
	public function ListByOrder( int $orderId ) : Collection;
	public function Find( int $id ) : Inventory;
	public function Add( string $title, string $comment ) : ?Inventory;
	public function Update( Inventory $apartment, string $title, string $comment ) : bool;
	public function PriceAdd( Inventory $inventory, float $priceValue ) : ?InventoryPrice;
}
