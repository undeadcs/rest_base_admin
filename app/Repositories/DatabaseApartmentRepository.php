<?php
namespace App\Repositories;

use App\Models\Apartment;
use App\Models\ApartmentPrice;
use App\Enums\ApartmentType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class DatabaseApartmentRepository implements ApartmentRepository {
	use OrderPeriodUtils;
	
	public function List( int $page = 1, int $pageSize = 25 ) : LengthAwarePaginator {
		return Apartment::orderBy( 'id', 'desc' )->with( 'currentPrice' )->paginate( $pageSize, [ '*' ], 'page', $page );
	}
	
	public function GetAll( ) : Collection {
		return Apartment::orderBy( 'id', 'desc' )->with( 'currentPrice' )->get( );
	}
	
	public function GetHouses( ) : Collection {
		return Apartment::orderBy( 'id', 'asc' )
			->where( 'type', ApartmentType::House )
			->with( 'currentPrice' )
			->get( );
	}
	
	public function Find( int $id ) : Apartment {
		return Apartment::findOrFail( $id );
	}
	
	public function Add( string $title, ApartmentType $type, int $number, int $capacity, string $comment ) : ?Apartment {
		$apartment = new Apartment;
		$apartment->title		= $title;
		$apartment->number		= $number;
		$apartment->type		= $type;
		$apartment->capacity	= $capacity;
		$apartment->comment		= $comment;
		
		return $apartment->save( ) ? $apartment : null;
	}
	
	public function Update( Apartment $apartment, string $title, ApartmentType $type, int $number, int $capacity, string $comment ) : bool {
		$update = false;
		
		if ( $apartment->title != $title ) {
			$update = true;
			$apartment->title = $title;
		}
		if ( $apartment->type != $type ) {
			$update = true;
			$apartment->type = $type;
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
	
	public function ListOrders( Apartment $apartment, int $page = 1, int $pageSize = 25 ) : LengthAwarePaginator {
		return $apartment->orders( )->orderBy( 'id', 'desc' )->paginate( $pageSize, [ '*' ], 'page', $page );
	}
	
	public function ListOrdersByPeriod( Apartment $apartment, Carbon $from, Carbon $to, int $page = 1, int $pageSize = 25 ) : LengthAwarePaginator {
		return $apartment->orders( )
			->orderBy( 'orders.id', 'asc' )
			->where( function( Builder $query ) use( $from, $to ) {
				$this->ApplyQueryPeriodCondition(
					$query,
					$from->format( 'Y-m-d H:i:s' ),
					$to->format( 'Y-m-d H:i:s'  )
				);
			} )
			->paginate( $pageSize, [ '*' ], 'page', $page );
	}
	
	public function ListOrdersWithCustomer( Apartment $apartment, int $page = 1, int $pageSize = 25 ) : LengthAwarePaginator {
		return $apartment->orders( )
			->orderBy( 'orders.id', 'asc' )
			->with( 'customer' )
			->with( 'apartmentPrice' )
			->paginate( $pageSize, [ '*' ], 'page', $page );
	}
}
