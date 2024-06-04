<?php

namespace App\Http\Controllers\Ui;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TopNavBar;
use App\Repositories\OrderRepository;
use Illuminate\View\View;
use App\Enums\TopPage;
use App\Models\Order;
use App\Repositories\ApartmentRepository;
use App\Repositories\InventoryRepository;
use App\Enums\OrderStatus;

class OrdersController extends Controller {
	protected TopNavBar $topNavBar;
	
	public function __construct( TopNavBar $topNavBar ) {
		$this->topNavBar = $topNavBar;
	}
	
	public function index( Request $request, OrderRepository $orders ) : View {
		return view( 'components.pages.'.TopPage::Orders->value, [
			'top_nav_items' => $this->topNavBar->items( ),
			'paginator' => $orders->List( ( int ) $request->input( 'page' ), 17 )
		] );
	}
	
	public function add( ApartmentRepository $apartments ) : View {
		$order = new Order;
		$order->from = now( );
		$order->to = now( )->modify( '+1 week' );
		
		return view( 'components.pages.order-form', [
			'top_nav_items'	=> $this->topNavBar->items( ),
			'order'			=> $order,
			'apartments'	=> $apartments->List( )
		] );
	}
	
	public function edit( Order $order, ApartmentRepository $apartments, InventoryRepository $inventories ) : View {
		$order->payments;
		$order->inventories;
		
		return view( 'components.pages.order-form', [
			'top_nav_items'	=> $this->topNavBar->items( ),
			'order'			=> $order,
			'apartments'	=> $apartments->List( ),
			'inventories'	=> $inventories->List( ),
			'statuses'		=> [
				OrderStatus::Pending->value => OrderStatus::Pending->title( ),
				OrderStatus::Active->value => OrderStatus::Active->title( ),
				OrderStatus::Closed->value => OrderStatus::Closed->title( ),
				OrderStatus::Canceled->value => OrderStatus::Canceled->title( )
			]
		] );
	}
}
