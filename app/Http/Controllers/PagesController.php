<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Enums\TopPage;
use App\Services\TopNavBar;
use App\Models\Customer;
use App\Repositories\CustomerRepository;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Repositories\ApartmentRepository;
use App\Enums\OrderStatus;
use App\Repositories\InventoryRepository;

class PagesController extends Controller {
	protected TopNavBar $topNavBar;
	
	public function __construct( TopNavBar $topNavBar ) {
		$this->topNavBar = $topNavBar;
	}
	
	public function main( ) : View {
		return view( 'components.pages.'.TopPage::Main->value, [
			'top_nav_items' => $this->topNavBar->items( )
		] );
	}
	
	public function orders( Request $request, OrderRepository $orders ) : View {
		$columns = [
			( object ) [ 'fieldName' => 'id',					'title' => __( 'Номер'			) ],
			( object ) [ 'fieldName' => 'customer_id',			'title' => __( 'Клиент'			) ],
			( object ) [ 'fieldName' => 'apartment_id',			'title' => __( 'Апартаменты'	) ],
			( object ) [ 'fieldName' => 'apartment_price_id',	'title' => __( 'Цена'			) ],
			( object ) [ 'fieldName' => 'status',				'title' => __( 'Статус'			) ],
			( object ) [ 'fieldName' => 'from',					'title' => __( 'С'				) ],
			( object ) [ 'fieldName' => 'to',					'title' => __( 'По'				) ],
			( object ) [ 'fieldName' => 'persons_number',		'title' => __( 'Кол-во человек'	) ]
		];
		$paginator = $orders->List( ( int ) $request->input( 'page' ), 17 );
		
		return view( 'components.pages.'.TopPage::Orders->value, [
			'top_nav_items' => $this->topNavBar->items( ),
			'orders' => $paginator->getCollection( ),
			'columns' => $columns,
			'baseUrl' => url( '/orders' ),
			'linkFieldName' => 'id',
			'editFieldName' => 'id',
			'newEntityUrl' => url( '/orders/add' ),
			'currentPage' => $paginator->currentPage( ),
			'lastPage' => $paginator->isEmpty( ) ? null : $paginator->lastPage( ),
			'customs' => [
				'customer_id'	=> fn( Order $order ) => '<a href="'.url( '/customers' ).'/'.$order->customer_id.'">'.$order->customer->name.'</a>',
				'apartment_id'	=> fn( Order $order ) => '<a href="'.url( '/apartments' ).'/'.$order->apartment_id.'">'.$order->apartment->title.'</a>',
				'apartment_price_id' => fn( Order $order ) => $order->apartmentPrice->price,
				'status'		=> fn( Order $order ) => $order->status->title( ),
				'from'			=> fn( Order $order ) => $order->from->format( 'd.m.Y' ),
				'to'			=> fn( Order $order ) => $order->to->format( 'd.m.Y' )
			]
		] );
	}
	
	public function newOrder( ApartmentRepository $apartments ) : View {
		return view( 'components.pages.order-form', [
			'top_nav_items'	=> $this->topNavBar->items( ),
			'order'			=> new Order,
			'apartments'	=> $apartments->List( )
		] );
	}
	
	public function editOrder( Order $order, ApartmentRepository $apartments, InventoryRepository $inventories ) : View {
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
