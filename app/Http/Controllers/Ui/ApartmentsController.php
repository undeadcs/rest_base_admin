<?php

namespace App\Http\Controllers\Ui;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Navigation;
use App\Repositories\ApartmentRepository;
use Illuminate\View\View;
use App\Enums\TopPage;
use App\Models\Apartment;
use App\Enums\ApartmentType;

class ApartmentsController extends Controller {
	protected Navigation $navigation;
	
	public function __construct( Navigation $navigation ) {
		$this->navigation = $navigation;
	}
	
	public function index( Request $request, ApartmentRepository $apartments ) : View {
		return view( 'components.pages.'.TopPage::Apartments->value, [
			'top_nav_items'	=> $this->navigation->items( TopPage::Apartments ),
			'paginator'		=> $apartments->List( ( int ) $request->input( 'page' ), 17 )
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
			'top_nav_items' => $this->navigation->items( TopPage::Apartments ),
			'apartment' => new Apartment,
			'types' => $this->ApartmentTypes( )
		] );
	}
	
	public function edit( Apartment $apartment, Request $request, ApartmentRepository $apartments ) : View {
		return view( 'components.pages.apartment-form', [
			'top_nav_items'	=> $this->navigation->items( TopPage::Apartments ),
			'apartment'		=> $apartment,
			'types'			=> $this->ApartmentTypes( ),
			'orders'		=> $apartments->ListOrdersWithCustomer( $apartment, ( int ) $request->input( 'page' ) )
		] );
	}
}
