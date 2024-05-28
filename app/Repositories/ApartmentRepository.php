<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Apartment;
use App\Models\ApartmentPrice;
use App\Enums\ApartmentType;

interface ApartmentRepository {
	public function List( ) : Collection;
	public function Find( int $id ) : Apartment;
	public function Add( string $title, ApartmentType $type, int $number, int $capacity, string $comment ) : ?Apartment;
	public function Update( Apartment $apartment, string $title, ApartmentType $type, int $number, int $capacity, string $comment ) : bool;
	public function PriceAdd( Apartment $apartment, float $price ) : ?ApartmentPrice;
}
