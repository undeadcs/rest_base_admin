<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\AddApartmentRequest;
use App\Http\Requests\UpdateApartmentRequest;
use App\Models\Apartment;
use App\Models\ApartmentPrice;

class ApartmentsController extends Controller {
	protected function SaveApartment( Apartment $apartment, array $input ) : void {
		$apartment->title = ( string ) $input[ 'title' ];
		$apartment->number = ( int ) $input[ 'number' ];
		$apartment->capacity = ( int ) $input[ 'capacity' ];
		$apartment->comment = ( string ) $input[ 'comment' ];
		
		$apartment->save( );
	}
	
	protected function SavePrice( Apartment $apartment, float $priceValue ) : void {
		$price = new ApartmentPrice;
		$price->apartment_id = $apartment->id;
		$price->created_at = now( );
		$price->price = $priceValue;
		$price->save( );
	}
	
	public function add( AddApartmentRequest $request ) : RedirectResponse {
		$input = $request->validated( );
		
		$apartment = new Apartment;
		$this->SaveApartment( $apartment, $input );
		$this->SavePrice( $apartment, ( float ) $input[ 'price' ] );
		
		return redirect( '/apartments' );
	}
	
	public function update( Apartment $apartment, UpdateApartmentRequest $request ) : RedirectResponse {
		$input = $request->validated( );
		
		$this->SaveApartment( $apartment, $input );
		
		$priceValue = ( float ) $input[ 'price' ];
		
		if ( ( float ) $apartment->currentPrice->price != $priceValue ) {
			$this->SavePrice( $apartment, ( float ) $priceValue );
		}
		
		return redirect( '/apartments' );
	}
}
