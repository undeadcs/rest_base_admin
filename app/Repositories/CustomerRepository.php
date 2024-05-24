<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Customer;

interface CustomerRepository {
	public function List( int $page = 1, int $pageSize = 25 ) : Collection;
	public function Find( int $id ) : Customer;
	public function Add( string $name, string $phoneNumber, string $carNumber, string $comment ) : ?Customer;
	public function Update( Customer $customer, string $name, string $phoneNumber, string $carNumber, string $comment ) : bool;
}
