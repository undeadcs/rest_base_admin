<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface CustomerRepository {
	public function List( int $page = 1, int $pageSize = 25 ) : LengthAwarePaginator;
	public function ListOrders( Customer $customer, int $page = 1, int $pageSize = 25 ) : LengthAwarePaginator;
	public function Find( int $id ) : Customer;
	public function Add( string $name, string $phoneNumber, string $carNumber, string $comment ) : ?Customer;
	public function Update( Customer $customer, string $name, string $phoneNumber, string $carNumber, string $comment ) : bool;
	public function SearchByPhoneNumberPart( string $phoneNumberPart, int $limit = 5 ) : Collection;
	public function SearchByCarNumberPart( string $carNumberPart, int $limit = 5 ) : Collection;
	public function SearchByNamePart( string $namePart, int $limit = 5 ) : Collection;
}
