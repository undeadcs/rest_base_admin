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
		$from = Carbon::createFromFormat( 'd.m.Y', $input[ 'from' ] )->format( 'Y-m-d' );
		$to = Carbon::createFromFormat( 'd.m.Y', $input[ 'to' ] )->format( 'Y-m-d' );
		
		if ( !$this->orders->Add( $customer, $apartment, $from, $to, $input[ 'persons_number' ], $input[ 'comment' ] ) ) {
			return redirect( )->back( )->withErrors( [ 'msg' => __( 'Провалилось сохранение записи о заявке' ) ] );
		}
		
		return redirect( '/orders' )->with( 'success', __( 'Заявка добавлена' ) );
	}
	
	public function update( Order $order, UpdateOrderRequest $request ) : RedirectResponse {
		$input = $request->validated( );
		$customer = $this->customers->Find( $input[ 'customer_id' ] );
		$apartment = $this->apartments->Find( $input[ 'apartment_id' ] );
		$from = Carbon::createFromFormat( 'd.m.Y', $input[ 'from' ] )->format( 'Y-m-d' );
		$to = Carbon::createFromFormat( 'd.m.Y', $input[ 'to' ] )->format( 'Y-m-d' );
		$status = OrderStatus::from( $input[ 'status' ] );
		
		if ( !$this->orders->Update( $order, $customer, $apartment, $status, $from, $to, $input[ 'persons_number' ], $input[ 'comment' ] ) ) {
			return redirect( )->back( )->withErrors( [ 'msg' => __( 'Провалилось сохранение записи о заявке' ) ] );
		}
		
		if ( isset( $input[ 'payments' ] ) && is_array( $input[ 'payments' ] ) ) {
			foreach( $input[ 'payments' ] as $row ) {
				if ( $row[ 'id' ] ) {
					if ( $payment = $order->payments->find( $row[ 'id' ] ) ) {
						$this->orders->PaymentUpdate( $payment, $row[ 'amount' ], $row[ 'comment' ] );
					}
				} else {
					$this->orders->PaymentAdd( $order, $row[ 'amount' ], $row[ 'comment' ] );
				}
			}
		}
		
		return redirect( '/orders' )->with( 'success', __( 'Заявка сохранена' ) );
	}
}
