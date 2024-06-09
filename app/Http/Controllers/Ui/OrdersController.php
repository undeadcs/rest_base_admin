<?php

namespace App\Http\Controllers\Ui;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Navigation;
use App\Repositories\OrderRepository;
use Illuminate\View\View;
use App\Enums\TopPage;
use App\Models\Order;
use App\Repositories\ApartmentRepository;
use App\Repositories\InventoryRepository;
use App\Enums\OrderStatus;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\CustomerRepository;

class OrdersController extends Controller {
	protected Navigation $navigation;
	
	public function __construct( Navigation $navigation ) {
		$this->navigation = $navigation;
	}
	
	public function index( Request $request, OrderRepository $orders ) : View {
		return view( 'components.pages.'.TopPage::Orders->value, [
			'top_nav_items'	=> $this->navigation->items( TopPage::Orders ),
			'paginator'		=> $orders->List( ( int ) $request->input( 'page' ), 17 )
		] );
	}
	
	protected function ResloveApartmentId( Request $request ) : int {
		if ( $request->exists( 'apartment_id' ) ) {
			return ( int ) $request->get( 'apartment_id' );
		}
		if ( $request->hasSession( ) && ( $apartmentId = $request->session( )->getOldInput( 'apartment_id' ) ) ) {
			return ( int ) $apartmentId;
		}
		
		return 0;
	}
	
	protected function ResolveCustomerId( Request $request ) : int {
		if ( $request->exists( 'customer_id' ) ) {
			return ( int ) $request->get( 'customer_id' );
		}
		if ( $request->hasSession( ) && ( $apartmentId = $request->session( )->getOldInput( 'customer_id' ) ) ) {
			return ( int ) $apartmentId;
		}
		
		return 0;
	}
	
	public function add( Request $request, ApartmentRepository $apartments, CustomerRepository $customers ) : View {
		$order = new Order;
		$order->from = now( )->setTime( 0, 0, 0 );
		$order->to = now( )->modify( '+1 week' )->setTime( 0, 0, 0 );
		
		if ( $request->has( 'from' ) ) {
			try {
				$order->from = Carbon::parse( $request->input( 'from' ) );
				$order->to = ( clone $order->from )->modify( '+1 week' );
			}
			catch( InvalidFormatException $e ) {
			}
		}
		if ( $apartmentId = $this->ResloveApartmentId( $request ) ) {
			try {
				$order->apartment( )->associate( $apartments->Find( $apartmentId ) );
			}
			catch( ModelNotFoundException $e ) {
			}
		}
		if ( $customerId = $this->ResolveCustomerId( $request ) ) {
			try {
				$order->customer( )->associate( $customers->Find( $customerId ) );
			}
			catch( ModelNotFoundException $e ) {
			}
		}
		
		return view( 'components.pages.order-form', [
			'top_nav_items'	=> $this->navigation->items( TopPage::Orders ),
			'order'			=> $order,
			'apartments'	=> $apartments->GetAll( )
		] );
	}
	
	public function edit( Order $order, ApartmentRepository $apartments, InventoryRepository $inventories ) : View {
		$order->payments;
		$order->inventories;
		
		return view( 'components.pages.order-form', [
			'top_nav_items'	=> $this->navigation->items( TopPage::Orders ),
			'order'			=> $order,
			'apartments'	=> $apartments->GetAll( ),
			'inventories'	=> $inventories->GetAll( ),
			'statuses'		=> [
				OrderStatus::Pending->value => OrderStatus::Pending->title( ),
				OrderStatus::Active->value => OrderStatus::Active->title( ),
				OrderStatus::Closed->value => OrderStatus::Closed->title( ),
				OrderStatus::Canceled->value => OrderStatus::Canceled->title( )
			]
		] );
	}
}
