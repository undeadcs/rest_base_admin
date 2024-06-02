<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Repositories\ApartmentRepository;
use App\Models\Apartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class ApartmentsController extends Controller {
	protected ApartmentRepository $apartments;
	
	public function __construct( ApartmentRepository $apartments ) {
		$this->apartments = $apartments;
	}
	
	public function index( ) : JsonResponse {
		return response( )->json( $this->apartments->List( )->toArray( ) );
	}
	
	public function instance( Apartment $apartment ) : JsonResponse {
		return response( )->json( $apartment->toArray( ) );
	}
	
	public function prices( Apartment $apartment ) : JsonResponse {
		return response( )->json( $apartment->prices->toArray( ) );
	}
	
	public function orders( Apartment $apartment, Request $request ) : JsonResponse {
		$paginator = $this->apartments->ListOrders( $apartment, ( int ) $request->input( 'page' ) );
		
		return response( )->json( [ 'totalCount' => $paginator->total( ), 'data' => $paginator->items( ) ] );
	}
	
	public function ordersByPeriod( Apartment $apartment, Request $request ) : JsonResponse {
		$input = $request->validate( [
			'from' => [ 'required', 'date_format:YmdHis' ],
			'to' => [ 'required', 'date_format:YmdHis' ]
		] );
		
		$from = Date::createFromFormat( 'YmdHis', $input[ 'from' ] );
		$to = Date::createFromFormat( 'YmdHis', $input[ 'to' ] );
		
		$paginator = $this->apartments->ListOrdersByPeriod( $apartment, $from, $to, ( int ) $request->input( 'page' ) );
		
		return response( )->json( [ 'totalCount' => $paginator->total( ), 'data' => $paginator->items( ) ] );
	}
}
