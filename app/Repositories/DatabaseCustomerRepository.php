<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class DatabaseCustomerRepository implements CustomerRepository {
	public function List( int $page = 1, int $pageSize = 25 ) : LengthAwarePaginator {
		return Customer::orderBy( 'name', 'asc' )->paginate( $pageSize, [ '*' ], 'page', $page );
	}
	
	public function Find( int $id ) : Customer {
		return Customer::findOrFail( $id );
	}
	
	public function Add( string $name, string $phoneNumber, string $carNumber, string $comment ) : ?Customer {
		$customer = new Customer;
		$customer->name = $name;
		$customer->phone_number = $phoneNumber;
		$customer->car_number = $carNumber;
		$customer->comment = $comment;
		
		return $customer->save( ) ? $customer : null;
	}
	
	public function Update( Customer $customer, string $name, string $phoneNumber, string $carNumber, string $comment ) : bool {
		$update = false;
		
		if ( $customer->name != $name ) {
			$update = true;
			$customer->name = $name;
		}
		if ( $customer->phone_number != $phoneNumber ) {
			$update = true;
			$customer->phone_number = $phoneNumber;
		}
		if ( $customer->car_number != $carNumber ) {
			$update = true;
			$customer->car_number = $carNumber;
		}
		if ( $customer->comment != $comment ) {
			$update = true;
			$customer->comment = $comment;
		}
		
		return !$update || $customer->save( );
	}
	
	public function SearchByPhoneNumberPart( string $phoneNumberPart, int $limit = 5 ) : Collection {
		return Customer::orderBy( 'name', 'asc' )->limit( $limit )->where( 'phone_number', 'LIKE', '%'.$phoneNumberPart.'%' )->get( );
	}
	
	public function SearchByCarNumberPart( string $carNumberPart, int $limit = 5 ) : Collection {
		return Customer::orderBy( 'name', 'asc' )->limit( $limit )->where( 'car_number', 'LIKE', '%'.$carNumberPart.'%' )->get( );
	}
	
	public function SearchByNamePart( string $namePart, int $limit = 5 ) : Collection {
		return Customer::orderBy( 'name', 'asc' )->limit( $limit )->where( 'name', 'LIKE', '%'.$namePart.'%' )->get( );
	}
}
