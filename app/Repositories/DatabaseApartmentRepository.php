<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Apartment;
use App\Models\ApartmentPrice;

class DatabaseApartmentRepository implements ApartmentRepository {
	public function List( ) : Collection {
		return Apartment::orderBy( 'number', 'desc' )->with( 'currentPrice' )->get( );
	}
	
	public function Find( int $id ) : Apartment {
		return Apartment::findOrFail( $id );
	}
	
	public function Add( string $title, int $number, int $capacity, string $comment ) : ?Apartment {
		$apartment = new Apartment;
		$apartment->title = $title;
		$apartment->number = $number;
		$apartment->capacity = $capacity;
		$apartment->comment = $comment;
		
		return $apartment->save( ) ? $apartment : null;
	}
	
	public function Update( Apartment $apartment, string $title, int $number, int $capacity, string $comment ) : bool {
		$update = false;
		
		if ( $apartment->title != $title ) {
			$update = true;
			$apartment->title = $title;
		}
		if ( $apartment->number != $number ) {
			$update = true;
			$apartment->number = $number;
		}
		if ( $apartment->capacity != $capacity ) {
			$update = true;
			$apartment->capacity = $capacity;
		}
		if ( $apartment->comment != $comment ) {
			$update = true;
			$apartment->comment = $comment;
		}
		
		return !$update || $apartment->save( );
	}
	
	public function PriceAdd( Apartment $apartment, float $price ) : ?ApartmentPrice {
		$instance = new ApartmentPrice;
		$instance->created_at = now( );
		$instance->price = $price;
		
		return $apartment->prices( )->save( $instance ) ? $instance : null;
	}
}
