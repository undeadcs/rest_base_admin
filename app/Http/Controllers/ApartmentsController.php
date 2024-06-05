<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ApartmentRequest;
use App\Models\Apartment;
use App\Repositories\ApartmentRepository;
use App\Enums\ApartmentType;

class ApartmentsController extends Controller {
	protected ApartmentRepository $apartments;
	
	public function __construct( ApartmentRepository $apartments ) {
		$this->apartments = $apartments;
	}
	
	public function add( ApartmentRequest $request ) : RedirectResponse {
		$input = $request->validated( );
		
		$apartment = $this->apartments->Add(
			$input[ 'title' ], ApartmentType::from( $input[ 'type' ] ), $input[ 'number' ], $input[ 'capacity' ], $input[ 'comment' ] ?? ''
		);
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
		
		if ( !$this->apartments->Update(
			$apartment, $input[ 'title' ], ApartmentType::from( $input[ 'type' ] ), $input[ 'number' ], $input[ 'capacity' ], $input[ 'comment' ] ?? ''
		) ) {
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
