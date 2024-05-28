<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Apartment;
use App\Enums\OrderStatus;

class DatabaseOrderRepository implements OrderRepository {
	public function List( int $page = 1, int $pageSize = 25 ) : LengthAwarePaginator {
		return Order::orderBy( 'updated_at', 'desc' )->orderBy( 'created_at', 'desc' )->orderBy( 'id', 'desc' )->paginate( $pageSize, [ '*' ], 'page', $page );
	}
	
	public function Find( int $id ) : Order {
		return Order::findOrFail( $id );
	}
	
	public function Add( Customer $customer, Apartment $apartment, string $from, string $to, int $personsNumber, string $comment ) : ?Order {
		$order = new Order;
		$order->from	= $from;
		$order->to		= $to;
		$order->persons_number = $personsNumber;
		$order->comment	= $comment;
		$order->status	= OrderStatus::Pending;
		
		$order->customer( )->associate( $customer );
		$order->apartment( )->associate( $apartment );
		$order->apartmentPrice( )->associate( $apartment->currentPrice );
		
		return $order->save( ) ? $order : null;
	}
		
	public function Update(
		Order $order, Customer $customer, Apartment $apartment, OrderStatus $status, string $from, string $to, int $personsNumber, string $comment
	) : bool {
		$update = false;
		
		if ( $order->customer_id != $customer->id ) {
			$update = true;
			$order->customer( )->associate( $customer );
		}
		if ( $order->apartment_id != $apartment->id ) {
			$update = true;
			$order->apartment( )->associate( $apartment );
		}
		if ( $order->status != $status ) {
			$update = true;
			$order->status = $status;
		}
		if ( $order->from != $from ) {
			$update = true;
			$order->from = $from;
		}
		if ( $order->to != $to ) {
			$update = true;
			$order->to = $to;
		}
		if ( $order->persons_number != $personsNumber ) {
			$update = true;
			$order->persons_number = $personsNumber;
		}
		if ( $order->comment != $comment ) {
			$update = true;
			$order->comment = $comment;
		}
		
		return !$update || $order->save( );
	}
}
