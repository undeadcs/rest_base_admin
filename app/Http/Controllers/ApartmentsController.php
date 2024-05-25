<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ApartmentRequest;
use App\Models\Apartment;
use App\Models\ApartmentPrice;
use App\Repositories\ApartmentRepository;

class ApartmentsController extends Controller {
	protected ApartmentRepository $apartments;
	
	public function __construct( ApartmentRepository $apartments ) {
		$this->apartments = $apartments;
	}
	
	public function add( ApartmentRequest $request ) : RedirectResponse {
		$input = $request->validated( );
		
		$apartment = $this->apartments->Add( $input[ 'title' ], ( int ) $input[ 'number' ], ( int ) $input[ 'capacity' ], $input[ 'comment' ] );
		if ( !$apartment ) {
			return redirect( )->back( )->withErrors( [ 'msg' => __( 'Провалилось сохранение записи' ) ] );
		}
		if ( !$this->apartments->PriceAdd( $apartment, ( float ) $input[ 'price' ] ) ) {
			return redirect( )->back( )->withErrors( [ 'msg' => __( 'Провалилось сохранение цены' ) ] );
		}
		
		return redirect( '/apartments' )->with( 'success', __( 'Апартаменты добавлены' ) );
	}
	
	public function update( Apartment $apartment, ApartmentRequest $request ) : RedirectResponse {
		$input = $request->validated( );
		
		if ( !$this->apartments->Update( $apartment, $input[ 'title' ], ( int ) $input[ 'number' ], ( int ) $input[ 'capacity' ], $input[ 'comment' ] ) ) {
			return redirect( )->back( )->withErrors( [ 'msg' => __( 'Провалилось сохранение записи' ) ] );
		}
		
		$newPrice = ( float ) $input[ 'price' ];
		
		if ( !$apartment->currentPrice || ( $apartment->currentPrice->price != $newPrice ) ) {
			if ( !$this->apartments->PriceAdd( $apartment, $newPrice ) ) {
				return redirect( )->back( )->withErrors( [ 'msg' => __( 'Провалилось сохранение цены' ) ] );
			}
		}
		
		return redirect( '/apartments' )->with( 'success', __( 'Апартаменты сохранены' ) );
	}
}
