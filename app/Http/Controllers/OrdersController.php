<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\OrderRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\ApartmentRepository;
use App\Http\Requests\AddOrderRequest;
use Illuminate\Http\RedirectResponse;
use App\Models\Order;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Customer;
use App\Enums\OrderStatus;

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
		$apartment = $this->apartments->Find( ( int ) $input[ 'apartment_id' ] );
		
		if ( !$this->orders->Add( $customer, $apartment, $input[ 'from' ], $input[ 'to' ], $input[ 'persons_number' ], $input[ 'comment' ] ) ) {
			return redirect( )->back( )->withErrors( [ 'msg' => __( 'Провалилось сохранение записи о заявке' ) ] );
		}
		
		return redirect( '/orders' )->with( 'success', __( 'Заявка добавлена' ) );
	}
	
	public function update( Order $order, UpdateOrderRequest $request ) : RedirectResponse {
		$input = $request->validated( );
		$customer = $this->customers->Find( $input[ 'customer_id' ] );
		$apartment = $this->apartments->Find( $input[ 'apartment_id' ] );
		$status = OrderStatus::from( $input[ 'status' ] );
		
		if ( !$this->orders->Update( $order, $customer, $apartment, $status, $input[ 'from' ], $input[ 'to' ], $input[ 'persons_number' ], $input[ 'comment' ] ) ) {
			return redirect( )->back( )->withErrors( [ 'msg' => __( 'Провалилось сохранение записи о заявке' ) ] );
		}
		
		return redirect( '/orders' )->with( 'success', __( 'Заявка сохранена' ) );
	}
}
