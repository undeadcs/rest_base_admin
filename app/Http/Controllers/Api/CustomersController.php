<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\CustomerRepository;
use Illuminate\Http\JsonResponse;
use App\Models\Customer;

class CustomersController extends Controller {
	protected CustomerRepository $customers;
	
	public function __construct( CustomerRepository $customers ) {
		$this->customers = $customers;
	}
	
	public function index( Request $request ) : JsonResponse {
		return response( )->json( $this->customers->List( ( int ) $request->input( 'page' ) )->toArray( ) );
	}
	
	public function instance( Customer $customer ) : JsonResponse {
		return response( )->json( $customer->toArray( ) );
	}
}
