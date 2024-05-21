<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Apartment;
use App\Models\ApartmentPrice;

class DatabaseApartmentRepository implements ApartmentRepository {
	public function List(  ) : Collection {
		return Apartment::orderBy( 'number', 'desc' )->get( );
	}
	
	public function Find( int $id ) : Apartment {
		return Apartment::findOrFail( $id );
	}
	
	public function Add( string $title, int $number, int $capacity, string $comment ) : ?Apartment {
		return null;
	}
	
	public function PriceAdd( Apartment $apartment, float $price ) : ?ApartmentPrice {
		return null;
	}
}
