<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Enums\TopPage;
use App\Services\TopNavBar;
use App\Models\Apartment;
use App\Models\Inventory;
use App\Models\Customer;
use App\Repositories\CustomerRepository;

class PagesController extends Controller {
	protected TopNavBar $topNavBar;
	
	public function __construct( TopNavBar $topNavBar ) {
		$this->topNavBar = $topNavBar;
	}
	
	public function main( ) : View {
		return view( 'components.pages.'.TopPage::Main->value, [ 'top_nav_items' => $this->topNavBar->items( ) ] );
	}
	
	public function apartments( ) : View { // @todo ApartmentsRepository
		$apartments = Apartment::orderBy( 'number', 'desc' )->with( 'currentPrice' )->get( );
		$apartments->each( function( Apartment $apartment ) {
			$apartment->price = $apartment->currentPrice ? $apartment->currentPrice->price : 0.0;
		} );
		$columns = [
			( object ) [ 'fieldName' => 'title',	'title' => __( 'Наименование'	) ],
			( object ) [ 'fieldName' => 'number',	'title' => __( 'Номер'			) ],
			( object ) [ 'fieldName' => 'capacity',	'title' => __( 'Вместимость'	) ],
			( object ) [ 'fieldName' => 'price',	'title' => __( 'Цена'			) ]
		];
		
		return view( 'components.pages.'.TopPage::Apartments->value, [
			'top_nav_items' => $this->topNavBar->items( ),
			'apartments' => $apartments,
			'columns' => $columns,
			'baseUrl' => url( '/apartments' ),
			'linkFieldName' => 'title',
			'editFieldName' => 'id',
			'newEntityUrl' => url( '/apartments/add' )
		] );
	}
	
	public function newApartment( ) : View {
		return view( 'components.pages.apartment-form', [ 'top_nav_items' => $this->topNavBar->items( ), 'apartment' => new Apartment ] );
	}
	
	public function editApartment( Apartment $apartment ) : View {
		return view( 'components.pages.apartment-form', [ 'top_nav_items' => $this->topNavBar->items( ), 'apartment' => $apartment ] );
	}
	
	public function reservs( ) : View {
		return view( 'components.pages.'.TopPage::Reservs->value, [ 'top_nav_items' => $this->topNavBar->items( ) ] );
	}
	
	public function customers( Request $request, CustomerRepository $customers ) : View {
		$columns = [
			( object ) [ 'fieldName' => 'name',			'title' => __( 'Имя'			) ],
			( object ) [ 'fieldName' => 'phone_number',	'title' => __( 'Телефон'		) ],
			( object ) [ 'fieldName' => 'car_number',	'title' => __( 'Номер машины'	) ]
		];
		$paginator = $customers->List( ( int ) $request->input( 'page' ), 17 );
		
		return view( 'components.pages.'.TopPage::Customers->value, [
			'top_nav_items' => $this->topNavBar->items( ),
			'customers' => $paginator->getCollection( ),
			'columns' => $columns,
			'baseUrl' => url( '/customers' ),
			'linkFieldName' => 'name',
			'editFieldName' => 'id',
			'newEntityUrl' => url( '/customers/add' ),
			'currentPage' => $paginator->currentPage( ),
			'lastPage' => $paginator->isEmpty( ) ? null : $paginator->lastPage( )
		] );
	}
	
	public function newCustomer( ) : View {
		return view( 'components.pages.customer-form', [ 'top_nav_items' => $this->topNavBar->items( ), 'customer' => new Customer ] );
	}
	
	public function editCustomer( Customer $customer ) : View {
		return view( 'components.pages.customer-form', [ 'top_nav_items' => $this->topNavBar->items( ), 'customer' => $customer ] );
	}
	
	public function inventories( ) : View {
		$inventories = Inventory::orderBy( 'title', 'asc' )->with( 'currentPrice' )->get( );
		$inventories->each( function( Inventory $inventory ) {
			$inventory->price = $inventory->currentPrice ? $inventory->currentPrice->price : 0.0;
		} );
		$columns = [
			( object ) [ 'fieldName' => 'title',	'title' => __( 'Наименование'	) ],
			( object ) [ 'fieldName' => 'price',	'title' => __( 'Цена'			) ]
		];
		
		return view( 'components.pages.'.TopPage::Inventories->value, [
			'top_nav_items' => $this->topNavBar->items( ),
			'inventories' => $inventories,
			'columns' => $columns,
			'baseUrl' => url( '/inventories' ),
			'linkFieldName' => 'title',
			'editFieldName' => 'id',
			'newEntityUrl' => url( '/inventories/add' )
		] );
	}
	
	public function newInventory( ) : View {
		return view( 'components.pages.inventory-form', [ 'top_nav_items' => $this->topNavBar->items( ), 'inventory' => new Inventory ] );
	}
	
	public function editInventory( Inventory $inventory ) : View {
		return view( 'components.pages.inventory-form', [ 'top_nav_items' => $this->topNavBar->items( ), 'inventory' => $inventory ] );
	}
}
