<?php

namespace App\Http\Controllers;

use App\Repositories\CustomerRepository;
use App\Http\Requests\CustomerRequest;
use Illuminate\Http\RedirectResponse;
use App\Models\Customer;

class CustomersController extends Controller {
	protected CustomerRepository $customers;
	
	public function __construct( CustomerRepository $customers ) {
		$this->customers = $customers;
	}
	
	public function add( CustomerRequest $request ) : RedirectResponse {
		$input = $request->validated( );
		
		if ( !$this->customers->Add( $input[ 'name' ], $input[ 'phone_number' ], $input[ 'car_number' ], $input[ 'comment' ] ) ) {
			return redirect( )->back( )->withErrors( [ 'msg' => __( 'Провалилось сохранение записи' ) ] );
		}
		
		return redirect( '/customers' )->with( 'success', __( 'Клиент добавлен' ) );
	}
	
	public function update( Customer $customer, CustomerRequest $request ) : RedirectResponse {
		$input = $request->validated( );
		
		if ( !$this->customers->Update( $customer, $input[ 'name' ], $input[ 'phone_number' ], $input[ 'car_number' ], $input[ 'comment' ] ) ) {
			return redirect( )->back( )->withErrors( [ 'msg' => __( 'Провалилось сохранение записи' ) ] );
		}
		
		return redirect( '/customers' )->with( 'success', __( 'Клиент сохранен' ) );
	}
}
