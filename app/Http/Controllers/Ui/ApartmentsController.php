<?php

namespace App\Http\Controllers\Ui;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TopNavBar;
use App\Repositories\ApartmentRepository;
use Illuminate\View\View;
use App\Enums\TopPage;
use App\Models\Apartment;
use App\Enums\ApartmentType;

class ApartmentsController extends Controller {
	protected TopNavBar $topNavBar;
	
	public function __construct( TopNavBar $topNavBar ) {
		$this->topNavBar = $topNavBar;
	}
	
	public function index( ApartmentRepository $apartments ) : View {
		$columns = [
			( object ) [ 'fieldName' => 'title',	'title' => __( 'Наименование'	) ],
			( object ) [ 'fieldName' => 'number',	'title' => __( 'Номер'			) ],
			( object ) [ 'fieldName' => 'capacity',	'title' => __( 'Вместимость'	) ],
			( object ) [ 'fieldName' => 'price',	'title' => __( 'Цена'			) ]
		];
		
		return view( 'components.pages.'.TopPage::Apartments->value, [
			'top_nav_items' => $this->topNavBar->items( ),
			'apartments' => $apartments->List( ),
			'columns' => $columns,
			'baseUrl' => url( '/apartments' ),
			'linkFieldName' => 'title',
			'editFieldName' => 'id',
			'newEntityUrl' => url( '/apartments/add' ),
			'customs' => [
				'price' => fn( Apartment $apartment ) => $apartment->currentPrice ? $apartment->currentPrice->price : '-'
			]
		] );
	}
	
	protected function ApartmentTypes( ) : array {
		return [
			ApartmentType::House->value		=> ApartmentType::House->title( ),
			ApartmentType::TentPlace->value	=> ApartmentType::TentPlace->title( ),
			ApartmentType::HotelRoom->value	=> ApartmentType::HotelRoom->title( )
		];
	}
	
	public function add( ) : View {
		return view( 'components.pages.apartment-form', [
			'top_nav_items' => $this->topNavBar->items( ),
			'apartment' => new Apartment,
			'types' => $this->ApartmentTypes( )
		] );
	}
	
	public function edit( Apartment $apartment ) : View {
		return view( 'components.pages.apartment-form', [
			'top_nav_items' => $this->topNavBar->items( ),
			'apartment' => $apartment,
			'types' => $this->ApartmentTypes( )
		] );
	}
}
