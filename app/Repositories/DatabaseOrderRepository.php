<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Apartment;
use Carbon\Carbon;
use App\Enums\OrderStatus;

class DatabaseOrderRepository implements OrderRepository {
	public function List( int $page = 1, int $pageSize = 25 ) : LengthAwarePaginator {
		return Order::orderBy( 'updated_at', 'desc' )->orderBy( 'created_at', 'desc' )->orderBy( 'id', 'desc' )->paginate( $pageSize, [ '*' ], 'page', $page );
	}
	
	public function Find( int $id ) : Order {
		return Order::findOrFail( $id );
	}
	
	public function Add( Customer $customer, Apartment $apartment, Carbon $from, Carbon $to, int $personsNumber, string $comment ) : ?Order {
		return null;
	}
		
	public function Update(
		Order $order, Customer $customer, Apartment $apartment, OrderStatus $status, Carbon $from, Carbon $to, int $personsNumber, string $comment
	) : bool {
		return false;
	}
}
