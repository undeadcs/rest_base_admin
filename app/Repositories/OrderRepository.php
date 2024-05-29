<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Apartment;
use App\Enums\OrderStatus;
use App\Models\Payment;

interface OrderRepository {
	public function List( int $page = 1, int $pageSize = 25 ) : LengthAwarePaginator;
	public function Find( int $id ) : Order;
	public function Add( Customer $customer, Apartment $apartment, string $from, string $to, int $personsNumber, string $comment ) : ?Order;
	public function Update(
		Order $order, Customer $customer, Apartment $apartment, OrderStatus $status, string $from, string $to, int $personsNumber, string $comment
	) : bool;
	public function PaymentAdd( Order $order, float $amount, string $comment ) : ?Payment;
	public function PaymentUpdate( Payment $payment, float $amount, string $comment ) : bool;
}
