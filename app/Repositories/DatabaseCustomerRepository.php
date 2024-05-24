<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Customer;

class DatabaseCustomerRepository implements CustomerRepository {
	public function List( int $page = 1, int $pageSize = 25 ) : Collection {
		return Customer::orderBy( 'name', 'asc' )->paginate( $pageSize, [ '*' ], 'page', $page )->getCollection( );
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
		if ( $customer->car_number != $phoneNumber ) {
			$update = true;
			$customer->car_number = $phoneNumber;
		}
		if ( $customer->comment != $comment ) {
			$update = true;
			$customer->comment = $comment;
		}
		
		return !$update || $customer->save( );
	}
}
