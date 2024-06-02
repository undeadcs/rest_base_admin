<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Apartment;
use App\Enums\OrderStatus;
use App\Models\Payment;
use App\Models\Inventory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class DatabaseOrderRepository implements OrderRepository {
	public function List( int $page = 1, int $pageSize = 25 ) : LengthAwarePaginator {
		return Order::orderBy( 'updated_at', 'desc' )->orderBy( 'created_at', 'desc' )->orderBy( 'id', 'desc' )->paginate( $pageSize, [ '*' ], 'page', $page );
	}
	
	public function ListByPeriod( Carbon $from, Carbon $to, int $page = 1, int $pageSize = 25 ) : LengthAwarePaginator {
		$fromStr = $from->format( 'Y-m-d H:i:s' );
		$toStr = $to->format( 'Y-m-d H:i:s' );
		
		$query = Order::orderBy( 'id', 'asc' );
		
		/*$query->whereBetween( 'from', [ $fromStr, $toStr ] ) // работает
			->orWhereBetween( 'to', [ $fromStr, $toStr ] )
			->orWhereRaw( '? BETWEEN "from" and "to"', $fromStr )
			->orWhereRaw( '? BETWEEN "from" and "to"', $toStr );*/
		//*
		$query->where( function( Builder $query ) use( $fromStr, $toStr ) { // ?
			$query->where( 'from', '<=', $fromStr )
				->where( 'to', '>=', $fromStr );
		} )->orWhere( function( Builder $query ) use( $fromStr, $toStr ) {
			$query->where( 'from', '>=', $fromStr )
				->where( 'from', '<=', $toStr );
		} );
		/*/
		$query->where( function( Builder $query ) use( $fromStr, $toStr ) { // beginInPeriod
			$query->where( 'from', '>=', $fromStr )
				->where( 'from', '<=', $toStr );
		} )->orWhere( function( Builder $query ) use( $fromStr, $toStr ) { // endInPeriod
			$query->where( 'to', '>=', $fromStr )
				->where( 'to', '<=', $toStr );
		} )->orWhere( function( Builder $query ) use( $fromStr, $toStr ) { // insidePeriod
			$query->where( 'from', '>=', $fromStr )
				->where( 'to', '<=', $toStr );
		} )->orWhere( function( Builder $query ) use( $fromStr, $toStr ) { // coverPeriod
			$query->where( 'from', '<=', $fromStr )
				->where( 'to', '>=', $toStr );
		} );
		//*/
		
		return $query->paginate( $pageSize, [ '*' ], 'page', $page );
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
			$order->apartmentPrice( )->associate( $apartment->currentPrice );
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
	
	public function PaymentAdd( Order $order, float $amount, string $comment ) : ?Payment {
		$payment = new Payment;
		$payment->amount = $amount;
		$payment->comment = $comment;
		
		$payment->order( )->associate( $order );
		
		return $payment->save( ) ? $payment : null;
	}
	
	public function PaymentUpdate( Payment $payment, float $amount, string $comment ) : bool {
		$update = false;
		
		if ( $payment->amount != $amount ) {
			$update = true;
			$payment->amount = $amount;
		}
		if ( $payment->comment != $comment ) {
			$update = true;
			$payment->comment = $comment;
		}
		
		return !$update || $payment->save( );
	}
	
	public function InventoryAdd( Order $order, Inventory $inventory, string $comment ) : bool {
		$order->inventories( )->attach( $inventory, [ 'comment' => $comment ] );
		
		return true;
	}
	
	public function InventoryUpdate( Order $order, Inventory $inventory, string $comment ) : bool {
		return ( bool ) $order->inventories( )->updateExistingPivot( $inventory->pivot->id, [ 'comment' => $comment ] );
	}
}
