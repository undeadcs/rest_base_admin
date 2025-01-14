<?php

namespace App\Http\Controllers;

use App\Repositories\OrderRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\ApartmentRepository;
use App\Http\Requests\AddOrderRequest;
use Illuminate\Http\RedirectResponse;
use App\Models\Order;
use App\Http\Requests\UpdateOrderRequest;
use App\Enums\OrderStatus;
use Illuminate\Support\Carbon;
use App\Repositories\InventoryRepository;

class OrdersController extends Controller {
	protected OrderRepository $orders;
	protected CustomerRepository $customers;
	protected ApartmentRepository $apartments;
	
	public function __construct( OrderRepository $orders, CustomerRepository $customers, ApartmentRepository $apartments ) {
		$this->orders		= $orders;
		$this->customers	= $customers;
		$this->apartments	= $apartments;
	}
	
	public function add( AddOrderRequest $request ) : RedirectResponse {
		$input = $request->validated( );
		$customer = $this->customers->Find( $input[ 'customer_id' ] );
		$apartment = $this->apartments->Find( $input[ 'apartment_id' ] );
		$from = Carbon::createFromFormat( 'd.m.Y H:i:s', $input[ 'from' ].' '.$input[ 'from_hour' ].':'.$input[ 'from_minute' ].':00' );
		$to = Carbon::createFromFormat( 'd.m.Y H:i:s', $input[ 'to' ].' '.$input[ 'to_hour' ].':'.$input[ 'to_minute' ].':00' );
		
		if ( !$this->orders->Add( $customer, $apartment, $from, $to, $input[ 'persons_number' ], $input[ 'comment' ] ?? '' ) ) {
			return redirect( )->back( )->withErrors( [ 'msg' => __( 'Провалилось сохранение записи о заявке' ) ] );
		}
		
		return redirect( '/orders' )->with( 'success', __( 'Заявка добавлена' ) );
	}
	
	public function update( Order $order, UpdateOrderRequest $request, InventoryRepository $inventories ) : RedirectResponse {
		$input = $request->validated( );
		$customer = $this->customers->Find( $input[ 'customer_id' ] );
		$apartment = $this->apartments->Find( $input[ 'apartment_id' ] );
		$from = Carbon::createFromFormat( 'd.m.Y H:i:s', $input[ 'from' ].' '.$input[ 'from_hour' ].':'.$input[ 'from_minute' ].':00' );
		$to = Carbon::createFromFormat( 'd.m.Y H:i:s', $input[ 'to' ].' '.$input[ 'to_hour' ].':'.$input[ 'to_minute' ].':00' );
		$status = OrderStatus::from( $input[ 'status' ] );
		
		if ( !$this->orders->Update( $order, $customer, $apartment, $status, $from, $to, $input[ 'persons_number' ], $input[ 'comment' ] ?? '' ) ) {
			return redirect( )->back( )->withErrors( [ 'msg' => __( 'Провалилось сохранение записи о заявке' ) ] );
		}
		
		if ( isset( $input[ 'inventories' ] ) && is_array( $input[ 'inventories' ] ) ) {
			foreach( $input[ 'inventories' ] as $row ) {
				if ( $row[ 'id' ] ) {
					if ( $inventory = $order->inventories->find( $row[ 'id' ] ) ) {
						$this->orders->InventoryUpdate( $order, $inventory, $row[ 'comment' ] ?? '' );
					}
				} else {
					$this->orders->InventoryAdd( $order, $inventories->Find( $row[ 'inventory_id' ] ), $row[ 'comment' ] ?? '' );
				}
			}
		}
		if ( isset( $input[ 'payments' ] ) && is_array( $input[ 'payments' ] ) ) {
			foreach( $input[ 'payments' ] as $row ) {
				if ( $row[ 'id' ] ) {
					if ( $payment = $order->payments->find( $row[ 'id' ] ) ) {
						$this->orders->PaymentUpdate( $payment, $row[ 'amount' ], $row[ 'comment' ] ?? '' );
					}
				} else {
					$this->orders->PaymentAdd( $order, $row[ 'amount' ], $row[ 'comment' ] ?? '' );
				}
			}
		}
		
		return redirect( '/orders' )->with( 'success', __( 'Заявка сохранена' ) );
	}
}
