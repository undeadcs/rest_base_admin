<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Enums\TopPage;
use App\Services\TopNavBar;
use App\Models\Apartment;

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
	
	public function customers( ) : View {
		return view( 'components.pages.'.TopPage::Customers->value, [ 'top_nav_items' => $this->topNavBar->items( ) ] );
	}
	
	public function inventories( ) : View {
		return view( 'components.pages.'.TopPage::Inventories->value, [ 'top_nav_items' => $this->topNavBar->items( ) ] );
	}
}
