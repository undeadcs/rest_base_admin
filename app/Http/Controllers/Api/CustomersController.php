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
		$paginator = $this->customers->List( ( int ) $request->input( 'page' ) );
		
		return response( )->json( [ 'totalCount' => $paginator->total( ), 'data' => $paginator->items( ) ] );
	}
	
	public function instance( Customer $customer ) : JsonResponse {
		return response( )->json( $customer->toArray( ) );
	}
	
	public function orders( Customer $customer, Request $request ) : JsonResponse {
		$paginator = $this->customers->ListOrders( $customer, ( int ) $request->input( 'page' ) );
		
		return response( )->json( [ 'totalCount' => $paginator->total( ), 'data' => $paginator->items( ) ] );
	}
	
	public function findForOrder( Request $request ) : JsonResponse {
		$input = $request->validate( [ 'term' => [ 'required', 'string', 'min:2' ] ] );
		$term = $input[ 'term' ];
		
		$alreadyMetCustomers = [ ];
		$data = [ ];
		
		$this->customers->SearchByPhoneNumberPart( $term )->each( function( Customer $customer ) use( &$alreadyMetCustomers, &$data ) {
			if ( isset( $alreadyMetCustomers[ $customer->id ] ) ) {
				return;
			}
			
			$alreadyMetCustomers[ $customer->id ] = $customer;
			
			$data[ ] = [
				'label' => $customer->phone_number.' ('.$customer->name.', '.$customer->car_number.')',
				'value' => $customer->id,
				'customer' => $customer->toArray( )
			];
		} );
		
		$this->customers->SearchByCarNumberPart( $term )->each( function( Customer $customer ) use( &$alreadyMetCustomers, &$data ) {
			if ( isset( $alreadyMetCustomers[ $customer->id ] ) ) {
				return;
			}
			
			$alreadyMetCustomers[ $customer->id ] = $customer;
			
			$data[ ] = [
				'label' => $customer->car_number.' ('.$customer->name.', '.$customer->phone_number.')',
				'value' => $customer->id,
				'customer' => $customer->toArray( )
			];
		} );
		
		$this->customers->SearchByNamePart( $term )->each( function( Customer $customer ) use( &$alreadyMetCustomers, &$data ) {
			if ( isset( $alreadyMetCustomers[ $customer->id ] ) ) {
				return;
			}
			
			$alreadyMetCustomers[ $customer->id ] = $customer;
			
			$data[ ] = [
				'label' => $customer->name.' ('.$customer->phone_number.', '.$customer->car_number.')',
				'value' => $customer->id,
				'customer' => $customer->toArray( )
			];
		} );
		
		return response( )->json( $data );
	}
}
