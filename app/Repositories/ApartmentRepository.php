<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Apartment;
use App\Models\ApartmentPrice;
use App\Enums\ApartmentType;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

interface ApartmentRepository {
	public function List( ) : Collection;
	public function Find( int $id ) : Apartment;
	public function Add( string $title, ApartmentType $type, int $number, int $capacity, string $comment ) : ?Apartment;
	public function Update( Apartment $apartment, string $title, ApartmentType $type, int $number, int $capacity, string $comment ) : bool;
	public function PriceAdd( Apartment $apartment, float $price ) : ?ApartmentPrice;
	public function ListOrders( Apartment $apartment, int $page = 1, int $pageSize = 25 ) : LengthAwarePaginator;
	public function ListOrdersByPeriod( Apartment $apartment, Carbon $from, Carbon $to, int $page = 1, int $pageSize = 25 ) : LengthAwarePaginator;
	public function ListOrdersWithCustomer( Apartment $apartment, int $page = 1, int $pageSize = 25 ) : LengthAwarePaginator;
}
