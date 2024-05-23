<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Apartment;
use App\Models\ApartmentPrice;

interface ApartmentRepository {
	public function List( ) : Collection;
	public function Find( int $id ) : Apartment;
	public function Add( string $title, int $number, int $capacity, string $comment ) : ?Apartment;
	public function Update( Apartment $apartment, string $title, int $number, int $capacity, string $comment ) : bool;
	public function PriceAdd( Apartment $apartment, float $price ) : ?ApartmentPrice;
}
